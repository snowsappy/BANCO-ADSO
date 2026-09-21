<?php
namespace App\Models\Transferencias;

class Transferencias{

    private int $id;

    private float $valor;

    private int $cuenta_origen_id;

    private int $cuenta_destino_id;

    private \DateTime $fecha;

    public function obtenerId(){

        return $this->id;
    }

    public function obtenerValor(){

        return $this->valor;
    }

    public function obtenerCuentaOrigenId(){

        return $this->cuenta_origen_id;
    }

    public function obtenerCuentaDestinoId(){

        return $this->cuenta_destino_id;
    }

    public function obtenerFecha(){

        return $this->fecha;
    }
}