<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Repositories\UsuarioRepo;
use App\Servicios\usuarios\UsuarioServicio;
use App\Controladores\SesionControlador;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$conexion = new App\Core\Conexion();
	$pdo = $conexion->getConexion();
	$repositorio = new UsuarioRepo($pdo);
	$servicio = new UsuarioServicio($repositorio);
	$controlador = new SesionControlador($servicio);
	$controlador->iniciar();
	exit;
}

require_once __DIR__ . '/../views/login.php';