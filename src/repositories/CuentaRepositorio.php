<?php

namespace App\Repositories;

use App\Models\Cuentas\Cuentas;
use PDO;

class CuentaRepositorio
{
    private PDO $conexion;
    public function __construct(
         PDO $conexion
    ) {
        $this->conexion = $conexion;
    }

    public function obtenerCuentaPorId(
        int $cuentaId
    ): Cuentas|false {

        $consulta = $this->conexion->prepare(
            'SELECT id, numero_cuenta, saldo, cliente_id
             FROM cuentas
             WHERE id = ?'
        );

        $consulta->execute([
            $cuentaId
        ]);

        return $this->hidratarCuenta($consulta->fetch(PDO::FETCH_ASSOC));
    }

    public function obtenerCuentaPorUsuario(
        int $usuarioId
    ): Cuentas|false {

        $consulta = $this->conexion->prepare(
            'SELECT cuentas.id,
                    cuentas.numero_cuenta,
                    cuentas.saldo,
                    cuentas.cliente_id
             FROM usuarios
             INNER JOIN cuentas
                ON cuentas.id = usuarios.cuenta_id
             WHERE usuarios.id = ?'
        );

        $consulta->execute([
            $usuarioId
        ]);

        return $this->hidratarCuenta($consulta->fetch(PDO::FETCH_ASSOC));
    }

    public function obtenerCuentaPorNumero(
        string $numeroCuenta
    ): Cuentas|false {

        $consulta = $this->conexion->prepare(
            'SELECT id, numero_cuenta, saldo, cliente_id
             FROM cuentas
             WHERE numero_cuenta = ?'
        );

        $consulta->execute([
            $numeroCuenta
        ]);

        return $this->hidratarCuenta($consulta->fetch(PDO::FETCH_ASSOC));
    }

    private function hidratarCuenta(array|false $fila): Cuentas|false
    {
        if ($fila === false) {
            return false;
        }

        return new Cuentas(
            (int) $fila['id'],
            $fila['numero_cuenta'],
            (float) $fila['saldo'],
            $fila['cliente_id'] === null ? null : (int) $fila['cliente_id']
        );
    }
}