<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'incident_hub');

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST,
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

    $pdo->exec("USE " . DB_NAME);

    $sql = file_get_contents(__DIR__ . '/base_de_datos.sql');
    $pdo->exec($sql);

} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>
