<?php
namespace App\Models\Transferencias;

class Transferencias{

    private int $id;

    private float $valor;

    private int $cuenta_origen_id;

    private int $cuenta_destino_id;

    private \DateTime $fecha;

    public function __construct(
        int $id,
        float $valor,
        int $cuenta_origen_id,
        int $cuenta_destino_id,
        \DateTimeInterface|string $fecha
    ) {
        $this->id = $id;
        $this->valor = $valor;
        $this->cuenta_origen_id = $cuenta_origen_id;
        $this->cuenta_destino_id = $cuenta_destino_id;
        $this->fecha = $fecha instanceof \DateTime
            ? $fecha
            : new \DateTime(
                $fecha instanceof \DateTimeInterface
                    ? $fecha->format('Y-m-d H:i:s')
                    : $fecha
            );
    }

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