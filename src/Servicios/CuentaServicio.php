<?php

namespace App\Servicios;

use App\Models\Cuentas\Cuentas;
use App\Repositories\CuentaRepositorio;

class CuentaServicio
{
    private CuentaRepositorio$repositorio;
    public function __construct(
         CuentaRepositorio $repositorio
    ) {
        $this->repositorio = $repositorio;
    }

    public function consultarSaldo(
        int $cuentaId
    ): Cuentas|false {

        return $this->repositorio
            ->obtenerCuentaPorId($cuentaId);
    }

    public function buscarPorNumero(
        string $numeroCuenta
    ): Cuentas|false {

        return $this->repositorio
            ->obtenerCuentaPorNumero($numeroCuenta);
    }
}