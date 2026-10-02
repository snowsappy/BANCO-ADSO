<?php

namespace App\Repositories;

use App\Models\Cuentas\Cuentas;
use App\Models\Transferencias\Transferencias;
use PDO;

class TransferenciaRepositorio
{
    private PDO $conexion;
    public function __construct( PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function registrar(int $origenId, int $destinoId, float $valor): void
    {
        $this->conexion->beginTransaction();

        try {
            $ids = [$origenId, $destinoId];
            sort($ids);
            $bloquear = $this->conexion->prepare(
                'SELECT id, numero_cuenta, saldo, cliente_id FROM cuentas
                 WHERE id IN (?, ?) ORDER BY id FOR UPDATE'
            );
            $bloquear->execute($ids);
            $cuentas = array_map(
                static fn (array $fila): Cuentas => new Cuentas(
                    (int) $fila['id'],
                    $fila['numero_cuenta'],
                    (float) $fila['saldo'],
                    $fila['cliente_id'] === null ? null : (int) $fila['cliente_id']
                ),
                $bloquear->fetchAll(PDO::FETCH_ASSOC)
            );

            if (count($cuentas) !== 2) {
                throw new \DomainException('La cuenta destino no existe');
            }

            $saldos = [];
            foreach ($cuentas as $cuenta) {
                $saldos[$cuenta->obtenerid()] = $cuenta->obtenerSaldo();
            }
            if ($saldos[$origenId] < $valor) {
                throw new \DomainException('Saldo insuficiente para realizar la transferencia');
            }

            $actualizar = $this->conexion->prepare(
                'UPDATE cuentas SET saldo = saldo - ? WHERE id = ?'
            );
            $actualizar->execute([$valor, $origenId]);
            $actualizar = $this->conexion->prepare(
                'UPDATE cuentas SET saldo = saldo + ? WHERE id = ?'
            );
            $actualizar->execute([$valor, $destinoId]);

            $insertar = $this->conexion->prepare(
                'INSERT INTO transferencias
                 (cuenta_origen_id, cuenta_destino_id, valor, fecha)
                 VALUES (?, ?, ?, NOW())'
            );
            $insertar->execute([$origenId, $destinoId, $valor]);
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
            'SELECT t.id, t.valor, t.cuenta_origen_id, t.cuenta_destino_id,
                    t.fecha, \'Enviada\' AS tipo,
                    destino.numero_cuenta AS cuenta_relacionada
             FROM transferencias t
             INNER JOIN cuentas destino ON destino.id = t.cuenta_destino_id
             WHERE t.cuenta_origen_id = ?
             UNION ALL
             SELECT t.id, t.valor, t.cuenta_origen_id, t.cuenta_destino_id,
                    t.fecha, \'Recibida\' AS tipo,
                    origen.numero_cuenta AS cuenta_relacionada
             FROM transferencias t
             INNER JOIN cuentas origen ON origen.id = t.cuenta_origen_id
             WHERE t.cuenta_destino_id = ?
             ORDER BY fecha DESC, id DESC'
        );
        $consulta->execute([$cuentaId, $cuentaId]);

        $resumen = $this->conexion->prepare(
            'SELECT
                SUM(CASE WHEN cuenta_origen_id = ? THEN 1 ELSE 0 END) AS enviadas,
                COALESCE(SUM(CASE WHEN cuenta_origen_id = ? THEN valor ELSE 0 END), 0) AS total_enviado,
                SUM(CASE WHEN cuenta_destino_id = ? THEN 1 ELSE 0 END) AS recibidas,
                COALESCE(SUM(CASE WHEN cuenta_destino_id = ? THEN valor ELSE 0 END), 0) AS total_recibido
             FROM transferencias
             WHERE cuenta_origen_id = ? OR cuenta_destino_id = ?'
        );
        $resumen->execute([$cuentaId, $cuentaId, $cuentaId, $cuentaId, $cuentaId, $cuentaId]);
        $datos = $resumen->fetch(PDO::FETCH_ASSOC);

        $movimientos = array_map(
            static fn (array $fila): array => [
                'transferencia' => new Transferencias(
                    (int) $fila['id'],
                    (float) $fila['valor'],
                    (int) $fila['cuenta_origen_id'],
                    (int) $fila['cuenta_destino_id'],
                    $fila['fecha']
                ),
                'tipo' => $fila['tipo'],
                'cuenta_relacionada' => $fila['cuenta_relacionada'],
            ],
            $consulta->fetchAll(PDO::FETCH_ASSOC)
        );

        return [
            'movimientos' => $movimientos,
            'enviadas' => (int) ($datos['enviadas'] ?? 0),
            'total_enviado' => (float) ($datos['total_enviado'] ?? 0),
            'recibidas' => (int) ($datos['recibidas'] ?? 0),
            'total_recibido' => (float) ($datos['total_recibido'] ?? 0),
        ];
    }
}
