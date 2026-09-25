<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de retirots</title>
</head>
<body>
    <h1>Historial de retiros</h1>

    <p>Total de retiros: <?= $historial['cantidad'] ?></p>
    <p>Total retirado: $<?= number_format($historial['total'], 2) ?></p>

    <?php if ($historial['movimientos'] === []): ?>
        <p>No hay retiros registrados.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr><th>Fecha</th><th>Valor</th></tr>
            </thead>
            <tbody>
                <?php foreach ($historial['movimientos'] as $movimiento): ?>
                    <tr>
                        <td><?= htmlspecialchars($movimiento['fecha'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>$<?= number_format((float) $movimiento['valor'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="index.php?action=saldo">Volver al panel</a></p>
</body>
</html>
