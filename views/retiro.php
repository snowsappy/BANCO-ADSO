<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Realizar retiro</title>
</head>
<body>
    <h1>Realizar retiro</h1>

    <?php if ($mensaje !== null): ?>
        <p><strong><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></strong></p>
    <?php endif; ?>

    <form method="post" action="index.php">
        <input type="hidden" name="action" value="retiro">
        <label>
            Valor a retirar:
            <input type="number" name="valor" min="0.01" step="0.01" required>
        </label>
        <br><br>
        <label>
            Contraseña:
            <input type="password" name="clave" required>
        </label>
        <br><br>
        <button type="submit">Confirmar retiro</button>
    </form>

    <p><a href="index.php?action=saldo">Volver al panel</a></p>
</body>
</html>
