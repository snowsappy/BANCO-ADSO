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

    public function registrar(int $origen_id, int $destino_id, float $valor): void
    {
        $this->conexion->beginTransaction();

        try {
            $ids = [$origen_id, $destino_id];
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
            if ($saldos[$origen_id] < $valor) {
                throw new \DomainException('Saldo insuficiente para realizar la transferencia');
            }

            $actualizar = $this->conexion->prepare(
                'UPDATE cuentas SET saldo = saldo - ? WHERE id = ?'
            );
            $actualizar->execute([$valor, $origen_id]);
            $actualizar = $this->conexion->prepare(
                'UPDATE cuentas SET saldo = saldo + ? WHERE id = ?'
            );
            $actualizar->execute([$valor, $destino_id]);

            $insertar = $this->conexion->prepare(
                'INSERT INTO transferencias
                 (cuenta_origen_id, cuenta_destino_id, valor, fecha)
                 VALUES (?, ?, ?, NOW())'
            );
            $insertar->execute([$origen_id, $destino_id, $valor]);
            $this->conexion->commit();
        } catch (\Throwable $exception) {
            if ($this->conexion->inTransaction()) {
                $this->conexion->rollBack();
            }
            throw $exception;
        }
    }

    public function historial(int $cuenta_id): array
    {
        $consulta = $this->conexion->prepare(
            'SELECT t.id, t.valor, t.cuenta_origen_id, t.cuenta_destino_id,
                    t.fecha,
                    CASE WHEN t.cuenta_origen_id = cuenta_actual.id
                         THEN \'Enviada\' ELSE \'Recibida\' END AS tipo,
                    CASE WHEN t.cuenta_origen_id = cuenta_actual.id
                         THEN destino.numero_cuenta ELSE origen.numero_cuenta
                    END AS cuenta_relacionada
             FROM cuentas cuenta_actual
             INNER JOIN transferencias t
                ON t.cuenta_origen_id = cuenta_actual.id
                OR t.cuenta_destino_id = cuenta_actual.id
             INNER JOIN cuentas origen ON origen.id = t.cuenta_origen_id
             INNER JOIN cuentas destino ON destino.id = t.cuenta_destino_id
             WHERE cuenta_actual.id = ?
             ORDER BY t.fecha DESC, t.id DESC'
        );
        $consulta->execute([$cuenta_id]);

        $movimientos = [];
        $enviadas = 0;
        $total_enviado_centavos = 0;
        $recibidas = 0;
        $total_recibido_centavos = 0;

        foreach ($consulta->fetchAll(PDO::FETCH_ASSOC) as $fila) {
            $valor = (float) $fila['valor'];
            $valor_centavos = (int) round($valor * 100);
            if ($fila['tipo'] === 'Enviada') {
                $enviadas++;
                $total_enviado_centavos += $valor_centavos;
            } else {
                $recibidas++;
                $total_recibido_centavos += $valor_centavos;
            }

            $movimientos[] = [
                'transferencia' => new Transferencias(
                    (int) $fila['id'],
                    $valor,
                    (int) $fila['cuenta_origen_id'],
                    (int) $fila['cuenta_destino_id'],
                    $fila['fecha']
                ),
                'tipo' => $fila['tipo'],
                'cuenta_relacionada' => $fila['cuenta_relacionada'],
            ];
        }

        return [
            'movimientos' => $movimientos,
            'enviadas' => $enviadas,
            'total_enviado' => $total_enviado_centavos / 100,
            'recibidas' => $recibidas,
            'total_recibido' => $total_recibido_centavos / 100,
        ];
    }
}
