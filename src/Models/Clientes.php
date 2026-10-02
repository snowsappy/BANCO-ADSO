<?php

namespace App\Models\Clientes;

class Clientes{

    private int $id;

    private string $nombre;

    public function obtenerId(){

        return $this->id;

    }

    public function obteneNombre(){

        return $this->nombre;
    }

    
}




