<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Panel bancario</title>
</head>

<body>

    <h1>Panel bancario</h1>

    <p>
        <strong>Número de cuenta:</strong>
        <?= htmlspecialchars($cuenta['numero_cuenta']) ?>
    </p>

    <p>
        <strong>Saldo disponible:</strong>
        $<?= number_format((float) $cuenta['saldo'], 2) ?>
    </p>

    <nav>
        <a href="index.php?action=retiro">Realizar retiro</a> |
        <a href="index.php?action=transferencia">Realizar transferencia</a> |
        <a href="index.php?action=historial_retiros">Historial de retiros</a> |
        <a href="index.php?action=historial_transferencias">Historial de transferencias</a>
    </nav>

</body>

</html>