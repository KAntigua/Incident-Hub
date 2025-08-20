<?php
session_start();
require_once '../config.php';

if(!isset($_SESSION['reportero_id'])) {
    header("Location: login.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $incidencia_id = $_POST['incidencia_id'] ?? null;
    $muertos = $_POST['muertos'] ?? 0;
    $heridos = $_POST['heridos'] ?? 0;
    $provincia_id = $_POST['provincia_id'] ?? null;
    $municipio_id = $_POST['municipio_id'] ?? null;
    $latitud = $_POST['latitud'] ?? null;
    $longitud = $_POST['longitud'] ?? null;
    $perdida_estimado = $_POST['perdida_estimado'] ?? 0;

    if(!$incidencia_id || !$provincia_id || !$municipio_id) {
        $_SESSION['error'] = "Debe completar todos los campos obligatorios.";
        header("Location: detalle_incidencia.php?id=".$incidencia_id);
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO correcciones 
        (incidencia_id, usuario_id, muertos, heridos, provincia_id, municipio_id, latitud, longitud, perdida_estimado, estado, creado_en)
        VALUES (:incidencia_id, :usuario_id, :muertos, :heridos, :provincia_id, :municipio_id, :latitud, :longitud, :perdida_estimado, 'pendiente', NOW())");

    $stmt->execute([
        ':incidencia_id' => $incidencia_id,
        ':usuario_id' => $_SESSION['reportero_id'],
        ':muertos' => $muertos,
        ':heridos' => $heridos,
        ':provincia_id' => $provincia_id,
        ':municipio_id' => $municipio_id,
        ':latitud' => $latitud,
        ':longitud' => $longitud,
        ':perdida_estimado' => $perdida_estimado
    ]);

    $_SESSION['success'] = "Corrección sugerida correctamente, quedará pendiente de validación.";
    header("Location: panel.php?id=".$incidencia_id);
    exit;
}


header("Location: panel.php");
exit;
