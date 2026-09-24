<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de transferencias</title>
</head>
<body>
    <h1>Historial de transferencias</h1>

    <p>Enviadas: <?= $historial['enviadas'] ?> |
        Total enviado: $<?= number_format($historial['total_enviado'], 2) ?></p>
    <p>Recibidas: <?= $historial['recibidas'] ?> |
        Total recibido: $<?= number_format($historial['total_recibido'], 2) ?></p>

    <?php if ($historial['movimientos'] === []): ?>
        <p>No hay transferencias registradas.</p>
    <?php else: ?>
        <table border="1">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Tipo</th>
                    <th>Cuenta relacionada</th>
                    <th>Valor</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historial['movimientos'] as $movimiento): ?>
                    <tr>
                        <td><?= htmlspecialchars($movimiento['fecha'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($movimiento['tipo'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($movimiento['cuenta_relacionada'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td>$<?= number_format((float) $movimiento['valor'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

    <p><a href="index.php?action=saldo">Volver al panel</a></p>
</body>
</html>
