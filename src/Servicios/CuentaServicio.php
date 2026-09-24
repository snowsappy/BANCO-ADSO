<?php

namespace App\Servicios;

use App\Repositories\CuentaRepo;

class CuentaServicio
{
    public function __construct(
        private CuentaRepo $repositorio
    ) {
    }

    public function consultarSaldo(
        int $cuentaId
    ): array|false {

        return $this->repositorio
            ->obtenerCuentaPorId($cuentaId);
    }

    public function buscarPorNumero(
        string $numeroCuenta
    ): array|false {

        return $this->repositorio
            ->obtenerCuentaPorNumero($numeroCuenta);
    }
}