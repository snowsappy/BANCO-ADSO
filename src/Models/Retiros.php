<?php
namespace App\Models\Retiros;

class Retiros{

    private int $id;

    private float $valor;

    private int $cuenta_id;
    private \DateTime $fecha;

    public function obtenerId(){

        return $this->id;
    }

    public function obtenerValor(){

        return $this->valor;
    }

    public function obtenerCuentaId(){

        return $this->cuenta_id;
    }

    public function obtenerFecha(){

        return $this->fecha;
    }
}