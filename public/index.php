<?php
echo "funciona??????";

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/controllers/usuarioControlador.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

use App\Core\Conexion;

use App\Repositories\UsuarioRepositorio;
use App\Repositories\CuentaRepositorio;
use App\Repositories\RetiroRepositorio;
use App\Repositories\TransferenciaRepositorio;

use App\Servicios\UsuarioServicio;
use App\Servicios\CuentaServicio;
use App\Servicios\RetiroServicio;
use App\Servicios\TransferenciaServicio;

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

$accionesProtegidas = [
    'saldo',
    'retiro',
    'transferencia',
    'historial_retiros',
    'historial_transferencias'
];

if (
    in_array($accion, $accionesProtegidas, true)
    && !isset($_SESSION['usuario_id'])
) {
    header('Location: index.php');
    exit;
}

$cuentaServicio = new CuentaServicio(
    new CuentaRepositorio($pdo)
);

$retiroServicio = new RetiroServicio(
    new UsuarioRepositorio($pdo),
    new RetiroRepositorio($pdo)
);

$transferenciaServicio = new TransferenciaServicio(
    new UsuarioRepositorio($pdo),
    new CuentaRepositorio($pdo),
    new TransferenciaRepositorio($pdo)
);

$retiroControlador = new RetiroControlador(
    $retiroServicio
);

$transferenciaControlador = new TransferenciaControlador(
    $transferenciaServicio
);

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'retiro'
) {
    $retiroControlador->formulario();
    exit;
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'transferencia'
) {
    $transferenciaControlador->formulario();
    exit;
}

if ($accion === '' || $accion === 'saldo') {

    if (!isset($_SESSION['usuario_id'])) {
        require_once __DIR__ . '/../views/login.php';
        exit;
    }

    $controlador = new CuentaControlador(
        $cuentaServicio
    );

    $controlador->mostrarPanel();

    exit;
}

if ($accion === 'retiro') {
    $retiroControlador->formulario();
    exit;
}

if ($accion === 'transferencia') {
    $transferenciaControlador->formulario();
    exit;
}

if ($accion === 'historial_retiros') {
    $retiroControlador->historial();
    exit;
}

if ($accion === 'historial_transferencias') {
    $transferenciaControlador->historial();
    exit;
}

require_once __DIR__ . '/../views/login.php';
exit;