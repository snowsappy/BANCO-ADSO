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
        $correo = $_POST['correo'];
        $clave = $_POST['clave'];

        $usuario = $this->servicio->iniciarSesion($correo, $clave);

        if ($usuario === false) {
            echo "Correo o contraseña incorrectos";
            return;
        }

        // Iniciar la sesión
        session_start();

        // Regenerar el identificador por seguridad
        session_regenerate_id(true);

        // Guardar información del usuario
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nombre'] = $usuario['nombre'];
        $_SESSION['usuario_correo'] = $usuario['correo'];

        echo "Sesión iniciada correctamente";
    }
}