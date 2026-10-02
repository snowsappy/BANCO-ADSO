<?php
namespace App\Models\Retiros;

class Retiros{

    private int $id;

    private float $valor;

    private int $cuenta_id;
    private \DateTime $fecha;

    public function __construct(
        int $id,
        float $valor,
        int $cuentaId,
        \DateTimeInterface|string $fecha
    ) {
        $this->id = $id;
        $this->valor = $valor;
        $this->cuenta_id = $cuentaId;
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

    public function obtenerCuentaId(){

        return $this->cuenta_id;
    }

    public function obtenerFecha(){

        return $this->fecha;
    }
}