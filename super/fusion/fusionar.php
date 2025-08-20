<?php
session_start();
require_once '../../config.php'; 
require_once '../../plantillas/plantillaval.php';
$plantilla = PlantillaVal::aplicar();

// ============================
// PROCESAR FUSIÓN
// ============================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fusionar'])) {
    $seleccionadas = $_POST['seleccionadas'] ?? [];
    if (count($seleccionadas) < 2) {
        die("<div class='alert alert-warning text-center'>Debes seleccionar al menos 2 incidencias para fusionar.</div>");
    }

    $placeholders = implode(',', array_fill(0, count($seleccionadas), '?'));
    $stmt = $pdo->prepare("SELECT * FROM incidencias WHERE id IN ($placeholders) AND validada = 1");
    $stmt->execute($seleccionadas);
    $incidencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($incidencias) < 2) {
        die("<div class='alert alert-warning text-center'>No hay suficientes incidencias validadas para fusionar.</div>");
    }

    // Elegir la más completa como base
    $base = null;
    $max_campos = -1;
    foreach ($incidencias as $i) {
        $llenos = 0;
        foreach (['titulo','descripcion','link_social','foto'] as $campo) {
            if (!empty($i[$campo])) $llenos++;
        }
        if ($llenos > $max_campos) {
            $max_campos = $llenos;
            $base = $i;
        }
    }

    // Fusionar las demás en la base
    foreach ($incidencias as $i) {
        if ($i['id'] == $base['id']) continue;

        $base['muertos'] += $i['muertos'];
        $base['heridos'] += $i['heridos'];
        $base['perdida'] += $i['perdida'];

        foreach (['descripcion','link_social','foto','titulo'] as $campo) {
            if (empty($base[$campo]) && !empty($i[$campo])) {
                $base[$campo] = $i[$campo];
            }
        }

        $pdo->prepare("UPDATE incidencias SET validada = 3 WHERE id = ?")->execute([$i['id']]);
    }

    $sql = "UPDATE incidencias SET muertos=?, heridos=?, perdida=?, descripcion=?, link_social=?, foto=?, titulo=? WHERE id=?";
    $pdo->prepare($sql)->execute([
        $base['muertos'],
        $base['heridos'],
        $base['perdida'],
        $base['descripcion'],
        $base['link_social'],
        $base['foto'],
        $base['titulo'],
        $base['id']
    ]);

    echo "<div class='alert alert-success text-center mb-4'>Incidencias fusionadas correctamente. Base: {$base['titulo']}</div>";
}

// ============================
// MOSTRAR INCIDENCIAS VALIDADAS
// ============================
// Unir con tablas relacionadas para mostrar nombres
$sql = "SELECT i.*, t.nombre AS tipo_nombre, p.nombre AS provincia_nombre, m.nombre AS municipio_nombre
        FROM incidencias i
        LEFT JOIN tipos_incidencias t ON i.tipo_id = t.id
        LEFT JOIN provincias p ON i.provincia_id = p.id
        LEFT JOIN municipios m ON i.municipio_id = m.id
        WHERE i.validada = 1
        ORDER BY i.fecha_ocurrencia DESC";
$incidencias = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <div class="card-body card p-4 mb-4 rounded shadow text-center hero-card">
        <h2 class="fw-bold text-white">Fusionar Incidencias Validadas</h2>
        <p class="mb-0 text-light">Selecciona las incidencias que deseas fusionar. La más completa será la base.</p>
    </div>

    <form method="post">
        <div class="card glass-card shadow-sm border-0 p-4 mb-4">
            <div class="table-responsive">
                <table class="table-fixed w-full">
                    <thead>
                        <tr class="bg-gray-200">
                            <th>Seleccionar</th>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Tipo</th>
                            <th>Provincia</th>
                            <th>Municipio</th>
                            <th>Muertos</th>
                            <th>Heridos</th>
                            <th>Pérdida</th>
                            <th>Link Social</th>
                            <th>Foto</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($incidencias as $i): ?>
                        <tr>
                            <td><input type="checkbox" name="seleccionadas[]" value="<?= $i['id'] ?>"></td>
                            <td><?= $i['id'] ?></td>
                            <td><?= htmlspecialchars($i['titulo']) ?></td>
                            <td><?= htmlspecialchars($i['descripcion']) ?></td>
                            <td><?= htmlspecialchars($i['tipo_nombre']) ?></td>
                            <td><?= htmlspecialchars($i['provincia_nombre']) ?></td>
                            <td><?= htmlspecialchars($i['municipio_nombre']) ?></td>
                            <td><?= $i['muertos'] ?></td>
                            <td><?= $i['heridos'] ?></td>
                            <td><?= number_format($i['perdida'],2) ?></td>
                            <td><?= htmlspecialchars($i['link_social']) ?></td>
                            <td>
                                <?php if(!empty($i['foto'])): ?>
                                    <img src="<?= htmlspecialchars($i['foto']) ?>" alt="Foto" style="width:50px;height:50px;object-fit:cover;">
                                <?php endif; ?>
                            </td>
                            <td><?= $i['fecha_ocurrencia'] ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <button type="submit" name="fusionar" class="btn custom-btn px-4 py-2">Fusionar Seleccionadas</button>
            </div>
        </div>
    </form>

    <div class="mt-4 text-center">
        <a href="../panel.php" class="btn custom-btn px-4 py-2">⬅ Volver Atrás</a>
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
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    color: #fff;
    transition: all 0.3s ease-in-out;
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

img {
    border-radius: 6px;
}
</style>
