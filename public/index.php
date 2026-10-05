<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/controllers/usuarioControlador.php';
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require __DIR__ . '/../routes/routes.php';