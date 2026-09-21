<?php

namespace App\Models\Cuentas;


class Cuentas{


    private int $id;

    private string $numero_cuenta;

    private float $saldo;

    private int $cliente_id;


    public function obtenerid(){

        return $this->id;
    }

    public function obtenerNumeroCuenta(){

        return $this->numero_cuenta;
    }
    public function obtenerSaldo(){

        return $this->saldo;
    }
    public function obtenerClienteId(){

        return $this->cliente_id;
    }
}