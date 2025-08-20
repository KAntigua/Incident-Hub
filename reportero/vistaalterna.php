<?php
require_once '../config.php';
require_once '../plantillas/plantillarep.php';
$plantilla = PlantillaRep::aplicar();

session_start();

// Verificar sesión
if (!isset($_SESSION['user_id'])) {
    die("Debes iniciar sesión para ver tus reportes.");
}

$reportero_id = $_SESSION['user_id'];

// Obtener incidencias aprobadas
$stmt = $pdo->prepare("SELECT i.id, i.titulo, i.descripcion, i.lat, i.lng, i.muertos, i.heridos, i.perdida, i.link_social, i.foto, i.fecha_ocurrencia, i.fecha_creacion,
                       t.nombre AS tipo, p.nombre AS provincia, m.nombre AS municipio, b.nombre AS barrio
                       FROM incidencias i
                       JOIN tipos_incidencias t ON i.tipo_id = t.id
                       JOIN provincias p ON i.provincia_id = p.id
                       JOIN municipios m ON i.municipio_id = m.id
                       JOIN barrios b ON i.barrio_id = b.id
                       WHERE i.reportero_id = ? AND i.validada = 1
                       ORDER BY i.fecha_creacion DESC");
$stmt->execute([$reportero_id]);
$incidencias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
    <h2 class="custom-title mb-4">Incidencias</h2>

    <?php if (empty($incidencias)): ?>
        <div class="alert alert-info text-center">No tienes incidencias aprobadas aún.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($incidencias as $inc): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card glass-card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($inc['titulo']) ?></h5>
                            <p class="card-text"><strong>Tipo:</strong> <?= htmlspecialchars($inc['tipo']) ?></p>
                            <p class="card-text"><strong>Ubicación:</strong> <?= htmlspecialchars($inc['provincia']) ?>, <?= htmlspecialchars($inc['municipio']) ?>, <?= htmlspecialchars($inc['barrio']) ?></p>
                            <p class="card-text"><strong>Fecha:</strong> <?= $inc['fecha_ocurrencia'] ?></p>
                            <p class="card-text"><strong>Muertos:</strong> <?= $inc['muertos'] ?> | <strong>Heridos:</strong> <?= $inc['heridos'] ?></p>
                            <p class="card-text"><strong>Pérdida RD$:</strong> <?= number_format($inc['perdida'],2) ?></p>
                            <?php if($inc['link_social']): ?>
                                <p><a href="<?= htmlspecialchars($inc['link_social']) ?>" target="_blank">Red Social</a></p>
                            <?php endif; ?>
                            <?php if($inc['foto']): ?>
                                <p><a href="<?= htmlspecialchars($inc['foto']) ?>" target="_blank">Foto</a></p>
                            <?php endif; ?>
                            <a href="detalleincidencia.php?id=<?= $inc['id'] ?>" class="btn custom-btn w-100 mt-2">Ver Detalles</a>
                            
                        </div>
                    </div>
                    
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
        <div class="text-center my-5">
        <a href="panel.php" class="custom-btn">⬅ Volver al Inicio</a>
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
    transform: translateY(-6px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.3), 0 0 15px rgba(255,255,255,0.2); 
}

.custom-title { 
    margin:0 auto 20px auto; 
    background-color:#9c710bff;
    padding:8px 16px;
    border-radius:30px; 
    color:#fff; 
    font-weight:bold; 
    text-align:center; 
}

.custom-btn {
    background-color:#9c710bff; 
    border:none;
    border-radius:30px; 
    padding:6px 16px; 
    color:#fff !important; 
    font-weight:bold; 
    transition: all 0.3s ease; 
}

.custom-btn:hover { 
    background-color:#6d4f07ff; 
    transform:scale(1.05); 
}

.card-title {
    font-weight:bold;
}

.card-text {
    font-size: 0.9rem;
}
</style>

