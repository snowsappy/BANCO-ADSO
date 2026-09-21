<?php

namespace App\BaseDeDatos;

use PDO;

class Conexion
{
    private static ?PDO $instancia = null;

    // constructor privado: nadie puede hacer "new Conexion()" desde afuera
    private function __construct()
    {
    }

    public static function obtener(): PDO
    {
        if (self::$instancia === null) {
            $config = require __DIR__ . '/../../config/basedatos.php';

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=%s',
                $config['host'],
                $config['puerto'],
                $config['basedatos'],
                $config['charset'],
            );

            self::$instancia = new PDO($dsn, $config['usuario'], $config['clave'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return self::$instancia;
    }
}
