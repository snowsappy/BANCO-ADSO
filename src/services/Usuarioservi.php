<?php

namespace App\Servicios\usuarios;

use App\Repositories\UsuarioRepo;

class UsuarioServicio
{
    private UsuarioRepo $repositorio;

    public function __construct(UsuarioRepo $repositorio)
    {
        $this->repositorio = $repositorio;
    }
    public function iniciarSesion(string $correo, string $clave)
{
    $usuario = $this->repositorio->buscarPorCorreo($correo);

    if ($usuario === false) {
        return false;
    }

    if (!password_verify($clave, $usuario['clave'])) {
        return false;
    }

    return $usuario;
}

}