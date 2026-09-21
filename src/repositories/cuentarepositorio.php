<?php

namespace App\Repositorios;

use PDO;

class CuentaRepositorio
{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function buscarPorUsuario(int $usuarioId): array|false
    {
        $sql = "SELECT id, numero_cuenta, saldo
                FROM cuentas
                WHERE usuario_id = ?";

        $consulta = $this->conexion->prepare($sql);
        $consulta->execute([$usuarioId]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
    }
}