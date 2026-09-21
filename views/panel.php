<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit;
}

echo "Bienvenido " . $_SESSION['usuario_nombre'];