<?php

namespace App\Controladores;

use App\Servicios\RetiroServicio;

class RetiroControlador
{
    private RetiroServicio $servicio;
    public function __construct(RetiroServicio $servicio)
    {
        $this->servicio = $servicio;
    }

    public function formulario(): void
    {
        $mensaje = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->servicio->realizar(
                    $this->usuarioId(),
                    $this->cuentaId(),
                    (string) ($_POST['clave'] ?? ''),
                    (string) ($_POST['valor'] ?? '')
                );
                header('Location: index.php?action=historial_retiros');
                exit;
            } catch (\DomainException $exception) {
                $mensaje = $exception->getMessage();
            }
        }
        require __DIR__ . '/../../views/retiro.php';
    }

    public function historial(): void
    {
        $historial = $this->servicio->historial($this->cuentaId());
        require __DIR__ . '/../../views/historial_retiros.php';
    }

    private function usuarioId(): int
    {
        return (int) $_SESSION['usuario_id'];
    }

    private function cuentaId(): int
    {
        return (int) $_SESSION['cuenta_id'];
    }
}
