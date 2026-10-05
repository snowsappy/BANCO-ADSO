<?php

use App\Core\Conexion;
use App\Repositories\CuentaRepositorio;
use App\Repositories\RetiroRepositorio;
use App\Repositories\TransferenciaRepositorio;
use App\Repositories\UsuarioRepositorio;
use App\Servicios\CuentaServicio;
use App\Servicios\RetiroServicio;
use App\Servicios\TransferenciaServicio;
use App\Servicios\UsuarioServicio;
use App\Controladores\CuentaControlador;
use App\Controladores\RetiroControlador;
use App\Controladores\TransferenciaControlador;
use App\Controladores\UsuarioControlador;

$pdo = Conexion::getConexion();
$accion = (string) ($_GET['action'] ?? '');

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'login'
) {
    $repositorio = new UsuarioRepositorio($pdo);
    $servicio = new UsuarioServicio($repositorio);
    $controlador = new UsuarioControlador($servicio);

    $controlador->iniciar();

    exit;
}

if ($accion === 'logout') {
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 60,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();

    header('Location: index.php');
    exit;
}

$sesion_valida = isset($_SESSION['usuario_id'], $_SESSION['cuenta_id'])
    && (int) $_SESSION['usuario_id'] > 0
    && (int) $_SESSION['cuenta_id'] > 0;

if (!$sesion_valida) {
    unset($_SESSION['usuario_id'], $_SESSION['cuenta_id'], $_SESSION['numero_cuenta']);
}

$acciones_protegidas = [
    'saldo',
    'retiro',
    'transferencia',
    'historial_retiros',
    'historial_transferencias'
];

if (
    in_array($accion, $acciones_protegidas, true)
    && !$sesion_valida
) {
    header('Location: index.php');
    exit;
}

$cuenta_servicio = new CuentaServicio(
    new CuentaRepositorio($pdo)
);

$retiro_servicio = new RetiroServicio(
    new UsuarioRepositorio($pdo),
    new RetiroRepositorio($pdo)
);

$transferencia_servicio = new TransferenciaServicio(
    new UsuarioRepositorio($pdo),
    new CuentaRepositorio($pdo),
    new TransferenciaRepositorio($pdo)
);

$retiro_controlador = new RetiroControlador(
    $retiro_servicio
);

$transferencia_controlador = new TransferenciaControlador(
    $transferencia_servicio
);

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'retiro'
) {
    $retiro_controlador->formulario();
    exit;
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'transferencia'
) {
    $transferencia_controlador->formulario();
    exit;
}

if ($accion === '' || $accion === 'saldo') {
    if (!$sesion_valida) {
        require_once __DIR__ . '/../views/login.php';
        exit;
    }

    $controlador = new CuentaControlador($cuenta_servicio);
    $controlador->mostrarPanel();

    exit;
}

if ($accion === 'retiro') {
    $retiro_controlador->formulario();
    exit;
}

if ($accion === 'transferencia') {
    $transferencia_controlador->formulario();
    exit;
}

if ($accion === 'historial_retiros') {
    $retiro_controlador->historial();
    exit;
}

if ($accion === 'historial_transferencias') {
    $transferencia_controlador->historial();
    exit;
}

require_once __DIR__ . '/../views/login.php';
exit;
