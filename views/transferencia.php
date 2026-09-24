<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Realizar transferencia</title>
</head>
<body>
    <h1>Realizar transferencia</h1>

    <?php if ($mensaje !== null): ?>
        <p><strong><?= htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') ?></strong></p>
    <?php endif; ?>

    <form method="post" action="index.php">
        <input type="hidden" name="action" value="transferencia">
        <label>
            Número de cuenta destino:
            <input type="text" name="numero_destino" required>
        </label>
        <br><br>
        <label>
            Valor a transferir:
            <input type="number" name="valor" min="0.01" step="0.01" required>
        </label>
        <br><br>
        <label>
            Contraseña:
            <input type="password" name="clave" required>
        </label>
        <br><br>
        <button type="submit">Confirmar transferencia</button>
    </form>

    <p><a href="index.php?action=saldo">Volver al panel</a></p>
</body>
</html>
