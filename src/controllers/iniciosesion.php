<?php

namespace App\Controladores;

use App\Servicios\UsuarioServicio;

class SesionControlador
{
    private UsuarioServicio $servicio;

    public function __construct(UsuarioServicio $servicio)
    {
        $this->servicio = $servicio;
    }

    public function iniciar()
    {
        $numeroCuenta = $_POST['numero_cuenta'];
        $clave = $_POST['clave'];

        $usuario = $this->servicio->iniciarSesion($numeroCuenta, $clave);

        if ($usuario === false) {
            echo "Número de cuenta o contraseña incorrectos";
            return;
        }

        session_start();

        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['numero_cuenta'] = $usuario['numero_cuenta'];

        echo "Sesión iniciada correctamente";
    }
}