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
        int $cuenta_id
    ): Cuentas|false {

        return $this->repositorio
            ->obtenerCuentaPorId($cuenta_id);
    }

    public function buscarPorNumero(
        string $numero_cuenta
    ): Cuentas|false {

        return $this->repositorio
            ->obtenerCuentaPorNumero($numero_cuenta);
    }
}