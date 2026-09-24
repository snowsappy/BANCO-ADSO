<?php

namespace App\Servicios;

use App\Repositories\CuentaRepo;
use App\Repositories\TransferenciaRepo;
use App\Repositories\UsuarioRepo;

class TransferenciaServicio
{
    public function __construct(
        private UsuarioRepo $usuarios,
        private CuentaRepo $cuentas,
        private TransferenciaRepo $transferencias
    ) {
    }

    public function realizar(
        int $usuarioId,
        string $clave,
        string $numeroDestino,
        string $valor
    ): void {
        $usuario = $this->usuarios->buscarPorId($usuarioId);
        if ($usuario === false || !password_verify($clave, $usuario['clave_hash'])) {
            throw new \DomainException('La contraseña de reconfirmación es incorrecta');
        }

        $destino = $this->cuentas->obtenerCuentaPorNumero(trim($numeroDestino));
        if ($destino === false) {
            throw new \DomainException('La cuenta destino no existe');
        }
        if ((int) $usuario['cuenta_id'] === (int) $destino['id']) {
            throw new \DomainException('La cuenta destino debe ser diferente a la cuenta de origen');
        }

        $monto = trim($valor);
        if ($monto === '' || !is_numeric($monto) || (float) $monto <= 0) {
            throw new \DomainException('El valor debe ser numérico y mayor que cero');
        }

        $this->transferencias->registrar(
            (int) $usuario['cuenta_id'],
            (int) $destino['id'],
            round((float) $monto, 2)
        );
    }

    public function historial(int $cuentaId): array
    {
        return $this->transferencias->historial($cuentaId);
    }
}
