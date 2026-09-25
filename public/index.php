<?php
echo "funciona??????";

require_once __DIR__ . '/../vendor/autoload.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

use App\Core\Conexion;

use App\Repositories\UsuarioRepo;
use App\Repositories\CuentaRepo;
use App\Repositories\RetiroRepo;
use App\Repositories\TransferenciaRepo;

use App\Servicios\UsuarioServicio;
use App\Servicios\CuentaServicio;
use App\Servicios\RetiroServicio;
use App\Servicios\TransferenciaServicio;

use App\Controladores\SesionControlador;
use App\Controladores\CuentaControlador;
use App\Controladores\RetiroControlador;
use App\Controladores\TransferenciaControlador;

$pdo = Conexion::getConexion();

$accion = (string) ($_GET['action'] ?? '');

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['action'] ?? '') === 'login'
) {
    $repositorio = new UsuarioRepo($pdo);
    $servicio = new UsuarioServicio($repositorio);
    $controlador = new SesionControlador($servicio);

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
    new CuentaRepo($pdo)
);

$retiroServicio = new RetiroServicio(
    new UsuarioRepo($pdo),
    new RetiroRepo($pdo)
);

$transferenciaServicio = new TransferenciaServicio(
    new UsuarioRepo($pdo),
    new CuentaRepo($pdo),
    new TransferenciaRepo($pdo)
);

$retiroControlador = new RetiroControlador(
    $cuentaServicio,
    $retiroServicio
);

$transferenciaControlador = new TransferenciaControlador(
    $cuentaServicio,
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