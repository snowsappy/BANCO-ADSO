<?php

namespace App\Repositories;

use App\Models\Usuarios;
use PDO;

class UsuarioRepositorio
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function buscarPorNumeroCuenta(
        string $numeroCuenta
    ): Usuarios|false {

        $sql = "SELECT
                    usuarios.id,
                    usuarios.cuenta_id,
                    usuarios.clave_hash
                FROM usuarios
                INNER JOIN cuentas
                    ON cuentas.id = usuarios.cuenta_id
                WHERE cuentas.numero_cuenta = ?";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([
            $numeroCuenta
        ]);

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($usuario === false) {
            return false;
        }

        return new Usuarios(
            (int) $usuario['id'],
            (int) $usuario['cuenta_id'],
            $usuario['clave_hash']
        );
    }

    public function buscarPorId(
        int $usuarioId
    ): Usuarios|false {

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

        $usuario = $consulta->fetch(PDO::FETCH_ASSOC);

        if ($usuario === false) {
            return false;
        }

        return new Usuarios(
            (int) $usuario['id'],
            (int) $usuario['cuenta_id'],
            $usuario['clave_hash']
        );
    }
}