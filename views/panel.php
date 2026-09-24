<?php

session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../public/index.php");
    exit;
}

echo "Bienvenido, cuenta " . htmlspecialchars(
    (string) $_SESSION['numero_cuenta'],
    ENT_QUOTES,
    'UTF-8'
);