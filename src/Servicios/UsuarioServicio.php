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
        string $numero_cuenta,
        string $clave
    ): Usuarios|false {

        $usuario =
            $this->repositorio
                ->buscarPorNumeroCuenta($numero_cuenta);

        if ($usuario === false) {
            return false;
        }

        if (
            !password_verify(
                $clave,
                $usuario->getClave()
            )
        ) {
            return false;
        }

        return $usuario;
    }
}