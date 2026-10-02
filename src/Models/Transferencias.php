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
        int $cuentaOrigenId,
        int $cuentaDestinoId,
        \DateTimeInterface|string $fecha
    ) {
        $this->id = $id;
        $this->valor = $valor;
        $this->cuenta_origen_id = $cuentaOrigenId;
        $this->cuenta_destino_id = $cuentaDestinoId;
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