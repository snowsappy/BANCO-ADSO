<?php

namespace App\Controladores;

use App\Servicios\TransferenciaServicio;

class TransferenciaControlador
{
    private TransferenciaServicio$servicio;
    public function __construct( TransferenciaServicio $servicio)
    {
        $this->servicio = $servicio;
    }

    public function formulario(): void
    {
        $mensaje = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->servicio->realizar(
                    (int) $_SESSION['usuario_id'],
                    (int) $_SESSION['cuenta_id'],
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
        $historial = $this->servicio->historial((int) $_SESSION['cuenta_id']);
        require __DIR__ . '/../../views/historial_transferencias.php';
    }
}
