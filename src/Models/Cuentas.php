<?php

namespace App\Models\Cuentas;


class Cuentas{


    private int $id;

    private string $numero_cuenta;

    private float $saldo;

    private ?int $cliente_id;

    public function __construct(
        int $id,
        string $numeroCuenta,
        float $saldo,
        ?int $clienteId
    ) {
        $this->id = $id;
        $this->numero_cuenta = $numeroCuenta;
        $this->saldo = $saldo;
        $this->cliente_id = $clienteId;
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