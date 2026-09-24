<?php

namespace App\Repositories;

use PDO;

class CuentaRepo
{
    public function __construct(
        private PDO $conexion
    ) {
    }

    public function obtenerCuentaPorId(
        int $cuentaId
    ): array|false {

        $consulta = $this->conexion->prepare(
            'SELECT id, numero_cuenta, saldo
             FROM cuentas
             WHERE id = ?'
        );

        $consulta->execute([
            $cuentaId
        ]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerCuentaPorUsuario(
        int $usuarioId
    ): array|false {

        $consulta = $this->conexion->prepare(
            'SELECT cuentas.id,
                    cuentas.numero_cuenta,
                    cuentas.saldo
             FROM usuarios
             INNER JOIN cuentas
                ON cuentas.id = usuarios.cuenta_id
             WHERE usuarios.id = ?'
        );

        $consulta->execute([
            $usuarioId
        ]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function obtenerCuentaPorNumero(
        string $numeroCuenta
    ): array|false {

        $consulta = $this->conexion->prepare(
            'SELECT id, numero_cuenta, saldo
             FROM cuentas
             WHERE numero_cuenta = ?'
        );

        $consulta->execute([
            $numeroCuenta
        ]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
}