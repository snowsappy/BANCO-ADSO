<?php

namespace App\Controladores;

use App\Servicios\CuentaServicio;

class CuentaControlador
{
    private CuentaServicio $servicio;

    public function __construct(CuentaServicio $servicio)
    {
        $this->servicio = $servicio;
    }

    public function consultarSaldo(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['usuario_id'])) {
            header("Location: /BancoADSO/vistas/login.php");
            exit;
        }

        $usuarioId = (int) $_SESSION['usuario_id'];

        $cuenta = $this->servicio->consultarSaldo($usuarioId);

        if ($cuenta === false) {
            $mensaje = "No tienes una cuenta registrada.";
            require_once __DIR__ . '/../../vistas/saldo.php';
            return;
        }

        require_once __DIR__ . '/../../vistas/saldo.php';
    }
}