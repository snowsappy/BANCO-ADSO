<?php

namespace App\Servicios;

use App\Repositorios\CuentaRepositorio;

class CuentaServicio
{
    private CuentaRepositorio $repositorio;

    public function __construct(CuentaRepositorio $repositorio)
    {
        $this->repositorio = $repositorio;
    }

    public function consultarSaldo(int $usuarioId): array|false
    {
        return $this->repositorio->buscarPorUsuario($usuarioId);
    }
}