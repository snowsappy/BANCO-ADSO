<?php

namespace App\Core;

use PDO;

class Conexion
{
    private PDO $conexion;

    public function __construct()
    {
        $this->conexion = new PDO(
            "mysql:host=localhost;dbname=banco;charset=utf8mb4",
            "root",
            "0000"
        );
    }

    public function getConexion(): PDO
    {
        return $this->conexion;
    }
}