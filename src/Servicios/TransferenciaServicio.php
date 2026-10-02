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
        int $usuarioId,
        int $cuentaOrigenId,
        string $clave,
        string $numeroDestino,
        string $valor
    ): void {
        $usuario = $this->usuarios->buscarPorId($usuarioId);
        if ($usuario === false || !password_verify($clave, password_hash($usuario->getClave(), PASSWORD_DEFAULT))) {
            echo('La contraseña de confirmación es incorrecta');
        }

        if ($usuario->get_cuenta() !== $cuentaOrigenId) {
            echo('La cuenta de la sesión no corresponde al usuario');
        }

        $destino = $this->cuentas->obtenerCuentaPorNumero(trim($numeroDestino));
        if ($destino === false) {
            echo('La cuenta de destino no existe');
        }
        if ($cuentaOrigenId === $destino->obtenerid()) {
            echo('La cuenta destino debe ser diferente a la cuenta de origen');
        }

        $monto = trim($valor);
        if ($monto === '' || !is_numeric($monto) || (float) $monto <= 0) {
            echo('El valor debe ser numérico y mayor que cero');
        }

        $this->transferencias->registrar(
            $cuentaOrigenId,
            $destino->obtenerid(),
            round((float) $monto, 2)
        );
    }

    public function historial(int $cuentaId): array
    {
        return $this->transferencias->historial($cuentaId);
    }
}
