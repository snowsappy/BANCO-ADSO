<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio de sesión</title>
</head>

<body>

    <h1>Iniciar sesión</h1>

    <form method="POST" action="../public/index.php">

        <label>Correo:</label>
        <input type="email" name="correo" required>

        <br><br>

        <label>Contraseña:</label>
        <input type="password" name="clave" required>

        <br><br>

        <button type="submit">Iniciar sesión</button>

    </form>

</body>
</html>