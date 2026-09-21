<?php
namespace App\Repositories;

use PDO;

class UsuarioRepo{
    private PDO $conexion;

    public function __construct(PDO $conexion)
    {
        $this->conexion = $conexion;
    }

    public function buscarPorCorreo(string $correo){

        $sql = "SELECT * FROM usuarios WHERE correo = ?";

        $consulta = $this->conexion->prepare($sql);

        $consulta->execute([$correo]);

        return $consulta->fetch(PDO::FETCH_ASSOC);
}
}