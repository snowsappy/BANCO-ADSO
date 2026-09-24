<?php

namespace App\Servicios;

use App\Repositories\RetiroRepo;
use App\Repositories\UsuarioRepo;

class RetiroServicio
{
    public function __construct(
        private UsuarioRepo $usuarios,
        private RetiroRepo $retiros
    ) {
    }

    public function realizar(int $usuarioId, string $clave, string $valor): void
    {
        $usuario = $this->usuarios->buscarPorId($usuarioId);
        $this->validarClave($usuario, $clave);
        $monto = $this->validarMonto($valor);
        $this->retiros->registrar((int) $usuario['cuenta_id'], $monto);
    }

    public function historial(int $cuentaId): array
    {
        return $this->retiros->historial($cuentaId);
    }

    private function validarClave(array|false $usuario, string $clave): void
    {
        if ($usuario === false || !password_verify($clave, $usuario['clave_hash'])) {
            throw new \DomainException('La contraseña de reconfirmación es incorrecta');
        }
    }

    private function validarMonto(string $valor): float
    {
        $valor = trim($valor);
        if ($valor === '' || !is_numeric($valor) || (float) $valor <= 0) {
            throw new \DomainException('El valor debe ser numérico y mayor que cero');
        }
        return round((float) $valor, 2);
    }
}
