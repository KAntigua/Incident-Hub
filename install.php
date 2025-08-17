<?php
require_once "config.php";

try {
    $sql = file_get_contents("base_de_datos.sql");
    $pdo->exec($sql);
    echo "✅ Base de datos y tablas creadas correctamente.";
} catch (PDOException $e) {
    die("❌ Error al crear tablas: " . $e->getMessage());
}
