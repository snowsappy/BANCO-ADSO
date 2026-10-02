<?php
if (!isset($cuenta)) {
    header('Location: ../public/index.php?action=saldo');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel bancario</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <h1>Panel de perfil</h1>
    <p>
        <strong>Número de cuenta:</strong>
        <?= htmlspecialchars($cuenta->obtenerNumeroCuenta(), ENT_QUOTES, 'UTF-8') ?>
    </p>

    <p>
        <strong>Saldo disponible:</strong>
        $<?= number_format($cuenta->obtenerSaldo(), 2) ?>
    </p>

    <nav>
        <a href="index.php?action=retiro">Realizar retiro</a> |
        <a href="index.php?action=transferencia">Realizar transferencia</a> |
        <a href="index.php?action=historial_retiros">Historial de retiros</a> |
        <a href="index.php?action=historial_transferencias">Historial de transferencias</a>
        <a href="index.php?action=logout">Cerrar sesión</a>
    </nav>

</body>

</html>
