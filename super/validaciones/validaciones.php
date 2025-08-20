<?php
session_start();
require_once '../../config.php'; 
require_once '../../plantillas/plantillaval.php';
$plantilla = PlantillaVal::aplicar();

if(isset($_GET['aprobar_corr'])) {
    $id = (int)$_GET['aprobar_corr'];

    // Obtener los datos de la corrección
    $stmt = $pdo->prepare("SELECT * FROM correcciones WHERE id = ?");
    $stmt->execute([$id]);
    $correc = $stmt->fetch(PDO::FETCH_ASSOC);

    if($correc){
        // Actualizar la incidencia con los valores sugeridos
        $update = $pdo->prepare("
            UPDATE incidencias 
            SET muertos = ?, heridos = ?, provincia_id = ?, municipio_id = ?, 
                lat = ?, lng = ?, perdida = ?
            WHERE id = ?
        ");
        $update->execute([
            $correc['muertos'],
            $correc['heridos'],
            $correc['provincia_id'],
            $correc['municipio_id'],
            $correc['latitud'],
            $correc['longitud'],
            $correc['perdida_estimado'],
            $correc['incidencia_id']
        ]);

        // Marcar la corrección como aprobada
        $pdo->prepare("UPDATE correcciones SET estado='aprobada', revisado_por=?, revisado_en=NOW() WHERE id = ?")
            ->execute([$_SESSION['usuario_id'], $id]);
    }

    header("Location: validaciones.php");
    exit;
}

if(isset($_GET['aprobar'])) {
    $id = (int)$_GET['aprobar'];
    $pdo->prepare("UPDATE incidencias SET validada = 1 WHERE id = ?")->execute([$id]);
    header("Location: validaciones.php");
    exit;
}

if(isset($_GET['rechazar'])) {
    $id = (int)$_GET['rechazar'];
    $pdo->prepare("UPDATE incidencias SET validada = 2 WHERE id = ?")->execute([$id]);
    header("Location: validaciones.php");
    exit;
}

// Aprobar o rechazar correcciones
if(isset($_GET['aprobar_corr'])) {
    $id = (int)$_GET['aprobar_corr'];
    $pdo->prepare("UPDATE correcciones SET estado='aprobada', revisado_por=?, revisado_en=NOW() WHERE id = ?")
        ->execute([$_SESSION['usuario_id'], $id]);
    header("Location: validaciones.php");
    exit;
}

if(isset($_GET['rechazar_corr'])) {
    $id = (int)$_GET['rechazar_corr'];
    $pdo->prepare("UPDATE correcciones SET estado='rechazada', revisado_por=?, revisado_en=NOW() WHERE id = ?")
        ->execute([$_SESSION['usuario_id'], $id]);
    header("Location: validaciones.php");
    exit;
}

// Incidencias pendientes
$incidencias = $pdo->query("
    SELECT i.id, i.titulo, i.descripcion, i.fecha_ocurrencia, i.lat, i.lng, 
           i.muertos, i.heridos, i.perdida, i.link_social, i.foto, u.nombre AS reportero
    FROM incidencias i
    JOIN usuarios u ON i.reportero_id = u.id
    WHERE i.validada = 0
    ORDER BY i.fecha_creacion DESC
")->fetchAll(PDO::FETCH_ASSOC);

// Correcciones pendientes
$correcciones = $pdo->query("
    SELECT c.id, c.incidencia_id, c.usuario_id, c.muertos, c.heridos, c.provincia_id, 
           c.municipio_id, c.perdida_estimado, c.latitud, c.longitud, u.nombre AS usuario_nombre, i.titulo AS incidencia_titulo
    FROM correcciones c
    JOIN usuarios u ON c.usuario_id = u.id
    JOIN incidencias i ON c.incidencia_id = i.id
    WHERE c.estado = 'pendiente'
    ORDER BY c.creado_en DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Validación de Incidencias y Correcciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        color: #fff;
        transition: all 0.3s ease-in-out;
        padding: 20px;
        border-radius: 16px;
    }
    .glass-card:hover {
        box-shadow: 0 15px 30px rgba(0,0,0,0.3), 0 0 15px rgba(255,255,255,0.2);
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
    table {
        width: 100%;
        text-align: center;      
    }
    table th, table td {
        color: #fff;
        text-align: center;      
        vertical-align: middle;  
        padding: 12px;
    }
    table th {
        font-weight: bold;
    }
    table tr:nth-child(even) {
        background-color: rgba(255,255,255,0.05);
    }
    a {
        color: #ffb400;
    }
    a:hover {
        color: #ffd966;
        text-decoration: none;
    }
    </style>
</head>
<body>

<div class="container mt-4">
    <div class="card-body card p-4 mb-4 rounded shadow text-center hero-card">
        <h2 class="fw-bold text-white">Validación de Incidencias</h2>
        <p class="mb-0 text-light">Aprobar o rechazar los reportes y sugerencias enviadas por los usuarios.</p>
    </div>

    <!-- Incidencias pendientes -->
    <?php if(empty($incidencias)): ?>
        <div class="alert alert-info">No hay incidencias pendientes.</div>
    <?php else: ?>
        <div class="card glass-card shadow-sm border-0 p-4 mb-4">
            <h5 class="text-white mb-3">Incidencias Pendientes</h5>
            <div class="table-responsive">
                <table class="table-fixed w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
                            <th>Reportero</th>
                            <th>Ubicación</th>
                            <th>Muertos</th>
                            <th>Heridos</th>
                            <th>Pérdida RD$</th>
                            <th>Redes/Foto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($incidencias as $inc): ?>
                        <tr>
                            <td><?= $inc['id'] ?></td>
                            <td><?= htmlspecialchars($inc['titulo']) ?></td>
                            <td><?= htmlspecialchars($inc['descripcion']) ?></td>
                            <td><?= $inc['fecha_ocurrencia'] ?></td>
                            <td><?= htmlspecialchars($inc['reportero']) ?></td>
                            <td>Lat: <?= $inc['lat'] ?>, Lng: <?= $inc['lng'] ?></td>
                            <td><?= $inc['muertos'] ?></td>
                            <td><?= $inc['heridos'] ?></td>
                            <td><?= number_format($inc['perdida'], 2) ?></td>
                            <td>
                                <?php if($inc['link_social']): ?><a href="<?= htmlspecialchars($inc['link_social']) ?>" target="_blank">Link</a><br><?php endif; ?>
                                <?php if($inc['foto']): ?><a href="<?= htmlspecialchars($inc['foto']) ?>" target="_blank">Foto</a><?php endif; ?>
                            </td>
                            <td>
                                <a href="?aprobar=<?= $inc['id'] ?>" class="btn custom-btn btn-sm mb-1">Aprobar</a>
                                <a href="?rechazar=<?= $inc['id'] ?>" class="btn custom-btn btn-sm btn-danger">Rechazar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <!-- Correcciones pendientes -->
    <?php if(!empty($correcciones)): ?>
        <div class="card glass-card shadow-sm border-0 p-4 mb-4">
            <h5 class="text-white mb-3">Correcciones Pendientes</h5>
            <div class="table-responsive">
                <table class="table-fixed w-full">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Incidencia</th>
                            <th>Usuario</th>
                            <th>Muertos</th>
                            <th>Heridos</th>
                            <th>Pérdida Estimada</th>
                            <th>Lat/Lng</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($correcciones as $c): ?>
                        <tr>
                            <td><?= $c['id'] ?></td>
                            <td><?= htmlspecialchars($c['incidencia_titulo']) ?></td>
                            <td><?= htmlspecialchars($c['usuario_nombre']) ?></td>
                            <td><?= $c['muertos'] ?></td>
                            <td><?= $c['heridos'] ?></td>
                            <td><?= number_format($c['perdida_estimado'],2) ?></td>
                            <td>Lat: <?= $c['latitud'] ?>, Lng: <?= $c['longitud'] ?></td>
                            <td>
                                <a href="?aprobar_corr=<?= $c['id'] ?>" class="btn custom-btn btn-sm mb-1">Aprobar</a>
                                <a href="?rechazar_corr=<?= $c['id'] ?>" class="btn custom-btn btn-sm btn-danger">Rechazar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>

    <div class="mt-4 text-center">
        <a href="../catalogos/panel.php" class="btn custom-btn px-4 py-2">⬅ Volver Atrás</a>
    </div>
</div>

</body>
</html> 