<?php
namespace App\Repositories;


use App\Core\Conexion;
class UsuarioRepo{
    private Conexion $conexion;

    public function __construct(Conexion $conexion)
    {
        $this->conexion = $conexion;
    }

    public function buscarPorNumeroCuenta(string $numeroCuenta)
    {
        $sql = "SELECT usuarios.*, cuentas.numero_cuenta, usuarios.clave_hash AS clave
                FROM usuarios
                INNER JOIN cuentas ON cuentas.id = usuarios.cuenta_id
                WHERE cuentas.numero_cuenta = ?";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([$numeroCuenta]);

        return $consulta->fetch(Conexion::FETCH_ASSOC);
    }
}