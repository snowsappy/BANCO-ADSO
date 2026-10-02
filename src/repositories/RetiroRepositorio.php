<?php

namespace App\Repositories;

use App\Models\Retiros\Retiros;
use PDO;

class RetiroRepositorio
{
    private PDO $conexion;
    public function __construct( PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function registrar(int $cuentaId, float $valor): void
    {
        $this->conexion->beginTransaction();

        try {
            $consulta = $this->conexion->prepare(
                'SELECT saldo FROM cuentas WHERE id = ? FOR UPDATE'
            );
            $consulta->execute([$cuentaId]);
            $cuenta = $consulta->fetch(PDO::FETCH_ASSOC);

            if ($cuenta === false || (float) $cuenta['saldo'] < $valor) {
                throw new \DomainException('Saldo insuficiente para realizar el retiro');
            }

            $actualizar = $this->conexion->prepare(
                'UPDATE cuentas SET saldo = saldo - ? WHERE id = ?'
            );
            $actualizar->execute([$valor, $cuentaId]);

            $insertar = $this->conexion->prepare(
                'INSERT INTO retiros (cuenta_id, valor, fecha) VALUES (?, ?, NOW())'
            );
            $insertar->execute([$cuentaId, $valor]);
            $this->conexion->commit();
        } catch (\Throwable $exception) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            throw $exception;
        }
    }

    public function historial(int $cuentaId): array
    {
        $consulta = $this->conexion->prepare(
            'SELECT id, cuenta_id, valor, fecha FROM retiros
             WHERE cuenta_id = ? ORDER BY fecha DESC, id DESC'
        );
        $consulta->execute([$cuentaId]);

        $resumen = $this->conexion->prepare(
            'SELECT COUNT(*) AS cantidad, COALESCE(SUM(valor), 0) AS total
             FROM retiros WHERE cuenta_id = ?'
        );
        $resumen->execute([$cuentaId]);
        $datos = $resumen->fetch(PDO::FETCH_ASSOC);

        $movimientos = array_map(
            static fn (array $fila): Retiros => new Retiros(
                (int) $fila['id'],
                (float) $fila['valor'],
                (int) $fila['cuenta_id'],
                $fila['fecha']
            ),
            $consulta->fetchAll(PDO::FETCH_ASSOC)
        );

        return [
            'movimientos' => $movimientos,
            'cantidad' => (int) ($datos['cantidad'] ?? 0),
            'total' => (float) ($datos['total'] ?? 0),
        ];
    }
}
