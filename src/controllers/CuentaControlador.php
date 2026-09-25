<?php

namespace App\Controladores;

use App\Servicios\CuentaServicio;

class CuentaControlador
{
    public function __construct(
        private CuentaServicio $servicio
    ) {
    }

    public function mostrarPanel(): void
    {
        if (!isset($_SESSION['cuenta_id'])) {
            header('Location: index.php');
            exit;
        }

        // La cuenta SIEMPRE sale de la sesión
        $cuentaId =
            (int) $_SESSION['cuenta_id'];

        // Se consulta el saldo ACTUAL en MySQL
        $cuenta =
            $this->servicio
                ->consultarSaldo($cuentaId);

        if ($cuenta === false) {
            http_response_code(404);
            exit('No se encontró la cuenta');
        }

        require __DIR__ .
            '/../../views/saldo.php';
    }
}