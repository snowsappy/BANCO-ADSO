<?php

namespace App\Servicios;

use App\Repositories\CuentaRepositorio;
use App\Repositories\TransferenciaRepositorio;
use App\Repositories\UsuarioRepositorio;

class TransferenciaServicio
{
       private     UsuarioRepositorio $usuarios;
       private     CuentaRepositorio $cuentas;
       private     TransferenciaRepositorio $transferencias;
    public function __construct(
         UsuarioRepositorio $usuarios,
            CuentaRepositorio $cuentas,
            TransferenciaRepositorio $transferencias
    ) {
        $this->usuarios = $usuarios;
        $this->cuentas = $cuentas;
        $this->transferencias = $transferencias;
    }

    public function realizar(
        int $usuario_id,
        int $cuenta_origen_id,
        string $clave,
        string $numero_destino,
        string $valor
    ): void {
        $usuario = $this->usuarios->buscarPorId($usuario_id);
        if (
            $usuario === false
            || !password_verify($clave, $usuario->getClave())
        ) {
            throw new \DomainException('La contraseña de confirmación es incorrecta');
        }

        if ($usuario->get_cuenta() !== $cuenta_origen_id) {
            throw new \DomainException('La cuenta de la sesión no corresponde al usuario');
        }

        $destino = $this->cuentas->obtenerCuentaPorNumero(trim($numero_destino));
        if ($destino === false) {
            throw new \DomainException('La cuenta de destino no existe');
        }
        if ($cuenta_origen_id === $destino->obtenerid()) {
            throw new \DomainException('La cuenta destino debe ser diferente a la cuenta de origen');
        }

        $monto = trim($valor);
        if ($monto === '' || !is_numeric($monto) || (float) $monto <= 0) {
            throw new \DomainException('El valor debe ser numérico y mayor que cero');
        }

        $monto = round((float) $monto, 2);
        if ($monto <= 0) {
            throw new \DomainException('El valor debe ser de al menos un centavo');
        }

        $this->transferencias->registrar(
            $cuenta_origen_id,
            $destino->obtenerid(),
            $monto
        );
    }

    public function historial(int $cuenta_id): array
    {
        return $this->transferencias->historial($cuenta_id);
    }
}
