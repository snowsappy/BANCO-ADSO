<?php

namespace App\Models\Cuentas;


class Cuentas{


    private int $id;

    private string $numero_cuenta;

    private float $saldo;

    private ?int $cliente_id;

    public function __construct(
        int $id,
        string $numero_cuenta,
        float $saldo,
        ?int $cliente_id
    ) {
        $this->id = $id;
        $this->numero_cuenta = $numero_cuenta;
        $this->saldo = $saldo;
        $this->cliente_id = $cliente_id;
    }

    public function obtenerid(){

        return $this->id;
    }

    public function obtenerNumeroCuenta(){

        return $this->numero_cuenta;
    }
    public function obtenerSaldo(){

        return $this->saldo;
    }
    public function obtenerClienteId(): ?int{
        return $this->cliente_id;
    }
}