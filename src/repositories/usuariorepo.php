<?php

namespace App\Repositories;

use PDO;

class UsuarioRepo
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function buscarPorNumeroCuenta(
        string $numeroCuenta
    ): array|false {

        $sql = "SELECT
                    usuarios.id,
                    usuarios.cuenta_id,
                    usuarios.clave_hash,
                    cuentas.numero_cuenta
                FROM usuarios
                INNER JOIN cuentas
                    ON cuentas.id = usuarios.cuenta_id
                WHERE cuentas.numero_cuenta = ?";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            $numeroCuenta
        ]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarPorId(
        int $usuarioId
    ): array|false {

        $sql = "SELECT
                    id,
                    cuenta_id,
                    clave_hash
                FROM usuarios
                WHERE id = ?";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            $usuarioId
        ]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
}