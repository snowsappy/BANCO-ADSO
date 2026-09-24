<?php

namespace App\Repositories;

use PDO;

class TransferenciaRepo
{
    public function __construct(private PDO $conexion)
    {
    }

    public function registrar(int $origenId, int $destinoId, float $valor): void
    {
        $this->conexion->beginTransaction();

        try {
            $ids = [$origenId, $destinoId];
            sort($ids);
            $bloquear = $this->conexion->prepare(
                'SELECT id, saldo FROM cuentas
                 WHERE id IN (?, ?) ORDER BY id FOR UPDATE'
            );
            $bloquear->execute($ids);
            $cuentas = $bloquear->fetchAll(PDO::FETCH_ASSOC);

            if (count($cuentas) !== 2) {
                throw new \DomainException('La cuenta destino no existe');
            }

            $saldos = [];
            foreach ($cuentas as $cuenta) {
                $saldos[(int) $cuenta['id']] = (float) $cuenta['saldo'];
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
            'SELECT t.id, t.valor, t.fecha, \'Enviada\' AS tipo,
                    destino.numero_cuenta AS cuenta_relacionada
             FROM transferencias t
             INNER JOIN cuentas destino ON destino.id = t.cuenta_destino_id
             WHERE t.cuenta_origen_id = ?
             UNION ALL
             SELECT t.id, t.valor, t.fecha, \'Recibida\' AS tipo,
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

        return [
            'movimientos' => $consulta->fetchAll(PDO::FETCH_ASSOC),
            'enviadas' => (int) ($datos['enviadas'] ?? 0),
            'total_enviado' => (float) ($datos['total_enviado'] ?? 0),
            'recibidas' => (int) ($datos['recibidas'] ?? 0),
            'total_recibido' => (float) ($datos['total_recibido'] ?? 0),
        ];
    }
}
