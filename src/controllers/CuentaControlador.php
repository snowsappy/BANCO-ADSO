<?php

namespace App\Controladores;

use App\Servicios\CuentaServicio;

class CuentaControlador
{
    private CuentaServicio $servicio;
    public function __construct(
         CuentaServicio $servicio
    ) {
        $this->servicio = $servicio;
    }

    public function mostrarPanel(): void
    {
        if (!isset($_SESSION['cuenta_id'])) {
            http_response_code(401);
            exit('Inicie sesión para consultar el panel');
        }

        
        $cuenta_id =
            (int) $_SESSION['cuenta_id'];


        $cuenta =
            $this->servicio
                ->consultarSaldo($cuenta_id);

        if ($cuenta === false) {
            http_response_code(404);
            exit('No se encontró la cuenta');
        }

        require __DIR__ .
            '/../../views/panel.php';
    }
}
