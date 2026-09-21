<?php

namespace App\Models;

class Usuarios{

    private int $id;

    private int $cuenta;

    private string $clave;

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

