<?php

namespace App\Servicios;

use App\Models\Usuarios;
use App\Repositories\UsuarioRepositorio;

class UsuarioServicio
{
    private UsuarioRepositorio $repositorio;

    public function __construct(
        UsuarioRepositorio $repositorio
    ) {
        $this->repositorio = $repositorio;
    }

    public function iniciarSesion(
        string $numeroCuenta,
        string $clave
    ): Usuarios|false {

        $usuario =
            $this->repositorio
                ->buscarPorNumeroCuenta($numeroCuenta);

        if ($usuario === false) {
            return false;
        }

        if (
            !password_verify(
                $clave,
                password_hash($usuario->getClave(), PASSWORD_DEFAULT)
            )
        ) {
            return false;
        }

        return $usuario;
    }
}