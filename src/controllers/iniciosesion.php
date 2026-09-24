<?php

namespace App\Controladores;

use App\Servicios\UsuarioServicio;

class SesionControlador
{
    private UsuarioServicio $servicio;

    public function __construct(
        UsuarioServicio $servicio
    ) {
        $this->servicio = $servicio;
    }

    public function iniciar(): void
    {
        $numeroCuenta = trim((string) ($_POST['numero_cuenta'] ?? ''));
        $clave = (string) ($_POST['clave'] ?? '');

        if ($numeroCuenta === '' || $clave === '') {
            echo "Debe ingresar el número de cuenta y la contraseña";
            return;
        }

        $usuario = $this->servicio->iniciarSesion(
            $numeroCuenta,
            $clave
        );

        if ($usuario === false) {
            echo "Número de cuenta o contraseña incorrectos";
            return;
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        session_regenerate_id(true);

        $_SESSION['usuario_id'] = (int) $usuario['id'];
        $_SESSION['cuenta_id'] = (int) $usuario['cuenta_id'];
        $_SESSION['numero_cuenta'] = $usuario['numero_cuenta'];

        header('Location: index.php');
        exit;
    }
}