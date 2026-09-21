<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Nucleo\Conexion;
use App\Repositorios\CuentaRepositorio;
use App\Servicios\CuentaServicio;
use App\Controladores\CuentaControlador;

$conexion = new Conexion();

$pdo = $conexion->getConexion();

if (isset($_GET['accion']) && $_GET['accion'] === 'saldo') {

    $repositorio = new CuentaRepositorio($pdo);

    $servicio = new CuentaServicio($repositorio);

    $controlador = new CuentaControlador($servicio);

    $controlador->consultarSaldo();

    exit;
}