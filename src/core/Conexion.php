<?php

namespace App\Core;
require_once __DIR__ . '/../../vendor/autoload.php';
use PDO;

class Conexion
{
    private static ?PDO $conexion=null;

    public static function getConexion() :PDO
    {
        if (self::$conexion === null) {
            $config = require __DIR__ . '/../../config/basedatos.php';
            self::$conexion = new PDO(
                    "mysql:host=localhost;dbname=banco;charset=utf8mb4",
                    $config['username'],
                    $config['password']
            );
        }     
        return  self ::$conexion;
    }

  
}