<?php

function escapar(string $texto): string
{
    return htmlspecialchars(
        $texto,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Consultar saldo</title>
</head>

<body>

    <h1>Consultar saldo</h1>

    <p>
        Usuario:
        <?= escapar($_SESSION['usuario_nombre']) ?>
    </p>

    <?php if (isset($mensaje)): ?>

        <p><?= escapar($mensaje) ?></p>

    <?php else: ?>

        <p>
            Número de cuenta:
            <?= escapar($cuenta['numero_cuenta']) ?>
        </p>

        <h2>
            Saldo:
            $<?= number_format((float) $cuenta['saldo'], 2, ',', '.') ?>
        </h2>

    <?php endif; ?>

    <a href="/BancoADSO/vistas/panel.php">
        Volver al panel
    </a>

</body>

</html>