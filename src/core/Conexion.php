<?php

namespace App\Core;

use PDO;

class Conexion
{
    private static ?PDO $conexion = null;

    public static function getConexion(): PDO
    {
        if (self::$conexion === null) {

            $config = require __DIR__ . '/../../config/basedatos.php';

            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['dbname'],
                $config['charset']
            );

            self::$conexion = new PDO(
                $dsn,
                $config['username'],
                $config['password']
            );
        }

        return self::$conexion;
    }
}