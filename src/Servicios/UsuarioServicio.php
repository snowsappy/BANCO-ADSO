<?php

namespace App\Servicios;

use App\Repositories\UsuarioRepo;

class UsuarioServicio
{
    private UsuarioRepo $repositorio;

    public function __construct(
        UsuarioRepo $repositorio
    ) {
        $this->repositorio = $repositorio;
    }

    public function iniciarSesion(
        string $numeroCuenta,
        string $clave
    ): array|false {

        $usuario =
            $this->repositorio
                ->buscarPorNumeroCuenta($numeroCuenta);

        if ($usuario === false) {
            return false;
        }

        if (
            !password_verify(
                $clave,
                $usuario['clave_hash']
            )
        ) {
            return false;
        }

        return $usuario;
    }
}