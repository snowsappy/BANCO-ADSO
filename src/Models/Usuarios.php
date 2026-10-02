<?php

namespace App\Models;

class Usuarios{

    private int $id;

    private int $cuenta;

    private string $clave;

    public function __construct(int $id, int $cuenta, string $clave)
    {
        $this->id = $id;
        $this->cuenta = $cuenta;
        $this->clave = $clave;
    }

    public function get_id(){

        return $this->id;

    }

    public  function get_cuenta(){

        return $this->cuenta;
    }

    public function getClave(){

        return $this->clave;

    }
}
