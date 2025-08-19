<?php
require_once '../../../config.php';
require_once '../../../plantillas/plantillaval.php';
$plantilla = PlantillaVal::aplicar();

$id = null;
$nombre = '';
$provincia_id = '';

$stmt = $pdo->query("SELECT id, nombre FROM provincias ORDER BY nombre");
$provincias = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM municipios WHERE id = ?");
    $stmt->execute([$id]);
    $municipio = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($municipio) {
        $nombre = $municipio['nombre'];
        $provincia_id = $municipio['provincia_id'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $provincia_id = $_POST['provincia_id'];

    if (!empty($nombre) && !empty($provincia_id)) {
        if (!empty($_POST['id'])) {
            $stmt = $pdo->prepare("UPDATE municipios SET nombre = ?, provincia_id = ? WHERE id = ?");
            $stmt->execute([$nombre, $provincia_id, $_POST['id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO municipios (nombre, provincia_id) VALUES (?, ?)");
            $stmt->execute([$nombre, $provincia_id]);
        }
        header("Location: municipios_ver.php");
        exit;
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}
?>

<div class="container mt-4">
    <div class="card-body card p-4 mb-4 rounded shadow text-center hero-card">
        <h2 class="fw-bold text-white"><?= $id ? "Editar Municipio" : "Agregar Municipio" ?></h2>
        <p class="mb-0 text-light"><?= $id ? "Modifica los datos del municipio." : "Ingrese un nuevo municipio." ?></p>
    </div>

    <div class="card glass-card shadow-sm border-0 p-4 mx-auto" style="max-width: 500px;">
        <?php if (!empty($error)): ?>
            <div class="mb-3 p-2 bg-red-600 text-white rounded"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST">
            <input type="hidden" name="id" value="<?= $id ?>">

            <div class="mb-4">
                <label class="block mb-2 font-semibold text-white" for="nombre">Nombre del Municipio</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($nombre) ?>" class="w-full p-2 rounded border border-gray-300">
            </div>

            <div class="mb-4">
                <label class="block mb-2 font-semibold text-white" for="provincia_id">Provincia</label>
                <select id="provincia_id" name="provincia_id" class="w-full p-2 rounded border border-gray-300">
                    <option value="">-- Seleccione Provincia --</option>
                    <?php foreach ($provincias as $prov): ?>
                        <option value="<?= $prov['id'] ?>" <?= $prov['id'] == $provincia_id ? 'selected' : '' ?>>
                            <?= htmlspecialchars($prov['nombre']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="text-center">
                <button type="submit" class="btn custom-btn px-6 py-2"><?= $id ? "Actualizar" : "Agregar" ?></button>
                <a href="municipios_ver.php" class="btn custom-btn px-6 py-2 bg-gray-600 hover:bg-gray-700 ml-2">Cancelar</a>
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
