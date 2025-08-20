<?php
require_once '../config.php';
require_once '../plantillas/plantillarep.php';
$plantilla = PlantillaRep::aplicar();

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID de incidencia no válido.");
}

$id = (int)$_GET['id'];

$stmt = $pdo->prepare("SELECT i.id, i.titulo, i.descripcion, i.lat, i.lng, i.muertos, i.heridos, 
                              i.perdida, i.link_social, i.foto, i.fecha_ocurrencia, i.fecha_creacion,
                              t.nombre AS tipo, p.nombre AS provincia, m.nombre AS municipio, b.nombre AS barrio,
                              u.nombre AS reportero
                       FROM incidencias i
                       JOIN tipos_incidencias t ON i.tipo_id = t.id
                       JOIN provincias p ON i.provincia_id = p.id
                       JOIN municipios m ON i.municipio_id = m.id
                       JOIN barrios b ON i.barrio_id = b.id
                       JOIN usuarios u ON i.reportero_id = u.id
                       WHERE i.id = ?");
$stmt->execute([$id]);
$incidencia = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$incidencia) {
    die("Incidencia no encontrada.");
}

// URL del mapa con lat/lng
$lat = $incidencia['lat'];
$lng = $incidencia['lng'];
$map_url = "https://www.google.com/maps?q={$lat},{$lng}&hl=es;z=15&output=embed";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Incidencia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
            padding: 20px;
        }
        .glass-card:hover {
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
        a.custom-btn {
            background-color:#9c710bff; 
            border:none;
            border-radius:30px; 
            padding:6px 16px; 
            color:#fff !important; 
            font-weight:bold; 
            transition: all 0.3s ease; 
            display:inline-block;
        }
        a.custom-btn:hover { 
            background-color:#6d4f07ff; 
            transform:scale(1.05); 
            text-decoration:none;
        }
        iframe {
            width: 100%;
            height: 400px;
            border-radius: 12px;
            border: 0;
        }
        .card-text { margin-bottom: 0.5rem; }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="custom-title">Detalle de Incidencia</h2>

    <div class="card glass-card shadow-sm mb-4">
        <div class="card-body">
            <h4><?= htmlspecialchars($incidencia['titulo']) ?></h4>

            <p class="card-text"><strong>Título:</strong> <?= htmlspecialchars($incidencia['titulo']) ?></p>
            <p class="card-text"><strong>Descripción:</strong> <?= nl2br(htmlspecialchars($incidencia['descripcion'])) ?></p>
            <p class="card-text"><strong>Tipo:</strong> <?= htmlspecialchars($incidencia['tipo']) ?> 
            <p class="card-text"><strong>Provincia:</strong> <?= htmlspecialchars($incidencia['provincia']) ?> 
            <p class="card-text"><strong>Municipio:</strong> <?= htmlspecialchars($incidencia['municipio']) ?> 
            <p class="card-text"><strong>Barrio:</strong> <?= htmlspecialchars($incidencia['barrio']) ?> 
            <p class="card-text"><strong>Latitud:</strong> <?= $incidencia['lat'] ?> | <strong>Longitud:</strong> <?= $incidencia['lng'] ?></p>
            <p class="card-text"><strong>Muertos:</strong> <?= $incidencia['muertos'] ?> | 
                <strong>Heridos:</strong> <?= $incidencia['heridos'] ?></p>
            <p class="card-text"><strong>Pérdida RD$:</strong> <?= number_format($incidencia['perdida'],2) ?></p>
            <?php if($incidencia['link_social']): ?>
                <p class="card-text"><strong>Link Social:</strong> <a href="<?= htmlspecialchars($incidencia['link_social']) ?>" target="_blank"><?= htmlspecialchars($incidencia['link_social']) ?></a></p>
            <?php endif; ?>
            <?php if($incidencia['foto']): ?>
                <p class="card-text"><strong>Foto:</strong> <a href="<?= htmlspecialchars($incidencia['foto']) ?>" target="_blank">Ver imagen</a></p>
            <?php endif; ?>
 
            <p class="card-text"><strong>Fecha de ocurrencia:</strong> <?= $incidencia['fecha_ocurrencia'] ?></p>
            <p class="card-text"><strong>Fecha de creación:</strong> <?= $incidencia['fecha_creacion'] ?></p>
        </div>
    </div>

    <div class="card glass-card shadow-sm mb-4">
        <div class="card-body">
            <h5>Ubicación en el mapa</h5>
            <iframe src="<?= $map_url ?>" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>

    <div class="text-center mb-5">
        <a href="vistaalterna.php" class="custom-btn">Volver</a>
    </div>
</div>
</body>
</html>
