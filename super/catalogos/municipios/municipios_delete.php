<?php
require_once '../../../config.php';
require_once '../../../plantillas/plantillaval.php';
$plantilla = PlantillaVal::aplicar();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: ID del municipio no especificado.");
}

$id = (int) $_GET['id'];

try {
    $stmt = $pdo->prepare("DELETE FROM municipios WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        if (!empty($_SERVER['HTTP_REFERER'])) {
            header("Location: " . $_SERVER['HTTP_REFERER']);
        } else {
            header("Location: municipios_ver.php?msg=eliminado");
        }
        exit;
    } else {
        echo "Error al intentar eliminar el municipio.";
    }
} catch (PDOException $e) {
    echo "Error en la base de datos: " . $e->getMessage();
}
?>
