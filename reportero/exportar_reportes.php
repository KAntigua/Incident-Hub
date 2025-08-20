<?php
require_once '../config.php';
require_once '../plantillas/plantillarep.php';
$plantilla = PlantillaRep::aplicar();


$provincias = $pdo->query("SELECT id, nombre FROM provincias ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
$tipos = $pdo->query("SELECT id, nombre FROM tipos_incidencias ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2 class="custom-title mb-4">Exportar uno o más Reportes</h2>

    <div class="card glass-card shadow-sm p-4">
        <form action="reportepdf.php" method="GET">
            <div class="mb-3">
                <label class="form-label text-white fw-bold">Filtrar por provincia:</label>
                <select name="provincia" class="form-select">
                    <option value="">Todas</option>
                    <?php foreach($provincias as $p): ?>
                        <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label text-white fw-bold">Filtrar por tipo:</label>
                <select name="tipo" class="form-select">
                    <option value="">Todos</option>
                    <?php foreach($tipos as $t): ?>
                        <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['nombre']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label text-white fw-bold">Fecha desde:</label>
                    <input type="date" name="fecha_desde" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label text-white fw-bold">Hasta:</label>
                    <input type="date" name="fecha_hasta" class="form-control">
                </div>
            </div>

            <div class="text-center mt-4">
                <button type="submit" class="btn custom-btn">Exportar PDF</button>
            </div>
        </form>
    </div>
     <div class="mt-4 text-center">
        <a href="../reportero/panel.php" class="btn custom-btn px-4 py-2">⬅ Volver Atrás</a>
    </div>
</div>

<style>
body { 
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background-color: #562b2bff; 
    min-height: 100vh;
}

.glass-card {
    background: rgba(255,255,255,0.1);
    border-radius:16px;
    backdrop-filter: blur(12px); 
    -webkit-backdrop-filter: blur(12px);
    color:#fff; 
    transition: all 0.3s ease-in-out;
}

.glass-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.3), 0 0 10px rgba(255,255,255,0.2); 
}

.custom-title { 
    margin:0 auto 20px auto; 
    background-color:#9c710bff;
    padding:10px 20px;
    border-radius:30px; 
    color:#fff; 
    font-weight:bold; 
    text-align:center; 
    max-width: fit-content;
}

.custom-btn {
    background-color:#9c710bff; 
    border:none;
    border-radius:30px; 
    padding:8px 20px; 
    color:#fff !important; 
    font-weight:bold; 
    transition: all 0.3s ease; 
}

.custom-btn:hover { 
    background-color:#6d4f07ff; 
    transform:scale(1.05); 
}
</style>
