<?php

namespace App\Controladores;

use App\Servicios\CuentaServicio;
use App\Servicios\TransferenciaServicio;

class TransferenciaControlador
{
    public function __construct(
        private CuentaServicio $cuentas,
        private TransferenciaServicio $servicio
    ) {
    }

    public function formulario(): void
    {
        $mensaje = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->servicio->realizar(
                    (int) $_SESSION['usuario_id'],
                    (string) ($_POST['clave'] ?? ''),
                    (string) ($_POST['numero_destino'] ?? ''),
                    (string) ($_POST['valor'] ?? '')
                );
                $mensaje = 'Transferencia realizada correctamente';
            } catch (\DomainException $exception) {
                $mensaje = $exception->getMessage();
            }
        }
        require __DIR__ . '/../../views/transferencia.php';
    }

    public function historial(): void
    {
        $cuenta = $this->cuentas->consultarSaldo((int) $_SESSION['usuario_id']);
        $historial = $this->servicio->historial((int) $cuenta['id']);
        require __DIR__ . '/../../views/historial_transferencias.php';
    }
}
