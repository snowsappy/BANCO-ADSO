<?php
namespace App\Core;

class Conexion{
     private PDO $conexion;

    public function __construct()
    {
        $this->conexion = new PDO(
            "mysql:host=localhost;dbname=banco_adso;charset=utf8mb4",
            "root",
            ""
        );
    }

    public function getConexion(): PDO
    {
        return $this->conexion;
    }
}