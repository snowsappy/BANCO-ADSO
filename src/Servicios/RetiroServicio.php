<?php

namespace App\Servicios;

use App\Models\Usuarios;
use App\Repositories\RetiroRepositorio;
use App\Repositories\UsuarioRepositorio;

class RetiroServicio
{
    private UsuarioRepositorio $usuarios;
    private RetiroRepositorio $retiros;
    public function __construct(
         UsuarioRepositorio $usuarios,
            RetiroRepositorio $retiros
    ) {
        $this->usuarios = $usuarios;
        $this->retiros = $retiros;
    }

    public function realizar(
        int $usuarioId,
        int $cuentaId,
        string $clave,
        string $valor
    ): void
    {
        $usuario = $this->usuarios->buscarPorId($usuarioId);
        $this->validarClave($usuario, $clave);
        if ($usuario->get_cuenta() !== $cuentaId) {
            echo('La cuenta de la sesión no corresponde al usuario');
        }

        $monto = $this->validarMonto($valor);
        $this->retiros->registrar($cuentaId, $monto);
    }

    public function historial(int $cuentaId): array
    {
        return $this->retiros->historial($cuentaId);
    }

    private function validarClave(Usuarios|false $usuario, string $clave): void
    {
        if ($usuario === false || !password_verify($clave, $usuario->getClave())) {
            throw new \DomainException('La contraseña de confirmación es incorrecta');
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
