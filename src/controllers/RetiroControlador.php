<?php

namespace App\Controladores;

use App\Servicios\CuentaServicio;
use App\Servicios\RetiroServicio;

class RetiroControlador
{
    public function __construct(
        private CuentaServicio $cuentas,
        private RetiroServicio $servicio
    ) {
    }

    public function formulario(): void
    {
        $mensaje = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $this->servicio->realizar(
                    $this->usuarioId(),
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
        $cuenta = $this->cuentas->consultarSaldo($this->usuarioId());
        $historial = $this->servicio->historial((int) $cuenta['id']);
        require __DIR__ . '/../../views/historial_retiros.php';
    }

    private function usuarioId(): int
    {
        return (int) $_SESSION['usuario_id'];
    }
}
