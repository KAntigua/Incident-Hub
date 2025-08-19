<?php
require_once '../../../config.php';
require_once '../../../plantillas/plantillaval.php';
$plantilla = PlantillaVal::aplicar();

$nombre = '';
$id = null;

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM provincias WHERE id = ?");
    $stmt->execute([$id]);
    $provincia = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($provincia) {
        $nombre = $provincia['nombre'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    
    if (!empty($nombre)) {
        if (!empty($_POST['id'])) {
            $stmt = $pdo->prepare("UPDATE provincias SET nombre = ? WHERE id = ?");
            $stmt->execute([$nombre, $_POST['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO provincias (nombre) VALUES (?)");
            $stmt->execute([$nombre]);
        }
        header("Location: ver.php");
        exit;
    } else {
        $error = "El nombre de la provincia no puede estar vacío.";
    }
}
?>

<div class="container mt-4">
    <div class="card-body card p-4 mb-4 rounded shadow text-center hero-card">
        <h2 class="fw-bold text-white"><?= $id ? "Editar Provincia" : "Agregar Provincia" ?></h2>
        <p class="mb-0 text-light"><?= $id ? "Modifica los datos de la provincia." : "Ingrese una nueva provincia." ?></p>
    </div>

    <div class="card glass-card shadow-sm border-0 p-4 mx-auto" style="max-width: 500px;">
        <?php if (!empty($error)): ?>
            <div class="mb-3 p-2 bg-red-600 text-white rounded"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="id" value="<?= $id ?>">

            <div class="mb-4">
                <label class="block mb-2 font-semibold text-white" for="nombre">Nombre de la Provincia</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>" class="w-full p-2 rounded border border-gray-300">
            </div>

            <div class="text-center">
                <button type="submit" class="btn custom-btn px-6 py-2"><?= $id ? "Actualizar" : "Agregar" ?></button>
                <a href="ver.php" class="btn custom-btn px-6 py-2 bg-gray-600 hover:bg-gray-700 ml-2">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background-color: #562b2bff;
    min-height: 100vh;
}

.hero-card {
    background: #a57d1fff;
    border: none;
}

.glass-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    color: #fff;
    transition: all 0.3s ease-in-out;
}

.custom-btn {
    background-color: #9c710bff;
    border: none;
    border-radius: 30px;
    padding: 6px 16px;
    color: #fff !important;
    font-weight: bold;
    transition: all 0.3s ease;
}
.custom-btn:hover {
    background-color: #6d4f07ff;
    transform: scale(1.05);
}
</style>
