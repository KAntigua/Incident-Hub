<?php
session_start();
require_once '../config.php';

if(!isset($_SESSION['reportero_id'])){
    die("No autorizado");
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $incidencia_id = $_POST['incidencia_id'];
    $comentario = trim($_POST['comentario']);
    $usuario_id = $_SESSION['reportero_id'];

    if($comentario){
        $stmt = $pdo->prepare("INSERT INTO comentarios (incidencia_id, usuario_id, comentario, fecha) VALUES (?,?,?,NOW())");
        $stmt->execute([$incidencia_id, $usuario_id, $comentario]);
    }

    header("Location: panel.php");
    exit;

}
