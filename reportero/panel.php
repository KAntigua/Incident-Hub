<?php
session_start(); 
require_once '../config.php';
require_once '../plantillas/plantillarep.php';
$plantilla = PlantillaRep::aplicar();

if (!isset($_SESSION['reportero_id'])) {
    die("Debes iniciar sesión para ver el panel de incidencias.");
}

$provincia_id = $_GET['provincia'] ?? '';
$tipo_id = $_GET['tipo'] ?? '';
$fecha_inicio = $_GET['fecha_inicio'] ?? date('Y-m-d', strtotime('-1 week'));
$fecha_fin = $_GET['fecha_fin'] ?? date('Y-m-d');
$titulo = $_GET['titulo'] ?? '';

$query = "SELECT i.*, t.nombre AS tipo_nombre, p.nombre AS provincia_nombre, 
                 m.nombre AS municipio_nombre, b.nombre AS barrio_nombre
          FROM incidencias i
          LEFT JOIN tipos_incidencias t ON i.tipo_id = t.id
          LEFT JOIN provincias p ON i.provincia_id = p.id
          LEFT JOIN municipios m ON i.municipio_id = m.id
          LEFT JOIN barrios b ON i.barrio_id = b.id
          WHERE i.validada = 1
          AND i.fecha_ocurrencia BETWEEN :inicio AND :fin";

$params = [
    ':inicio' => $fecha_inicio . ' 00:00:00',
    ':fin' => $fecha_fin . ' 23:59:59'
];

if ($provincia_id) { 
    $query .= " AND i.provincia_id = :provincia"; 
    $params[':provincia']=$provincia_id; 
}
if ($tipo_id) { 
    $query .= " AND i.tipo_id = :tipo"; 
    $params[':tipo']=$tipo_id; 
}
if ($titulo) {
    $query .= " AND i.titulo LIKE :titulo";
    $params[':titulo'] = "%$titulo%";
}

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$incidencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

$provincias = $pdo->query("SELECT * FROM provincias")->fetchAll(PDO::FETCH_ASSOC);
$tipos = $pdo->query("SELECT * FROM tipos_incidencias")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <div class="card-body card p-4 mb-4 rounded shadow text-center hero-card">
        <h2 class="fw-bold text-white">Panel Principal de Incidencias</h2>
        <p class="mb-0 text-light">Visualiza, filtra y administra las incidencias de las últimas 24 horas.</p>
    </div>

    <form class="row g-3 mb-3" method="GET">
        <div class="col-md-3">
            <label>Provincia</label>
            <select class="form-select" name="provincia">
                <option value="">Todas</option>
                <?php foreach($provincias as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $provincia_id==$p['id']?'selected':'' ?>><?= $p['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label>Tipo</label>
            <select class="form-select" name="tipo">
                <option value="">Todos</option>
                <?php foreach($tipos as $t): ?>
                    <option value="<?= $t['id'] ?>" <?= $tipo_id==$t['id']?'selected':'' ?>><?= $t['nombre'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label>Desde</label>
            <input type="date" class="form-control" name="fecha_inicio" value="<?= $fecha_inicio ?>">
        </div>
        <div class="col-md-3">
            <label>Hasta</label>
            <input type="date" class="form-control" name="fecha_fin" value="<?= $fecha_fin ?>">
        </div>
        <div class="col-md-3">
            <label>Título</label>
            <input type="text" class="form-control" name="titulo" value="<?= htmlspecialchars($titulo) ?>">
        </div>
        <div class="col-md-12">
            <button class="btn custom-btn mt-2">Filtrar</button>
        </div>
    </form>

    <div id="mapa" style="height: 600px;" class="mb-4"></div>

    <div class="modal fade" id="detalleModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalle de la Incidencia</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modal-body-content"></div>
            </div>
        </div>
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

.custom-btn { 
    background-color: #9c710bff; 
    color: #fff !important; 
    border-radius: 30px; 
    font-weight: bold; 
    transition: all 0.3s; 
}

.custom-btn:hover { 
    background-color: #6d4f07ff; 
    transform: scale(1.05); 
}

.form-select,
.form-control {
    background-color: rgba(255,255,255,0.1); 
    color: #fff; 
    border: 1px solid rgba(255,255,255,0.3);
}

.form-select option {
    color: #000; 
}

.form-select:focus,
.form-control:focus {
    background-color: rgba(255,255,255,0.2);
    color: #fff;
    border-color: #ffb400;
    outline: none;
}

form label {
    color: #fff;
    font-weight: 500; 
}
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

<script>
var mapa = L.map('mapa').setView([18.5, -69.9], 7); 
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '&copy; OpenStreetMap' }).addTo(mapa);
var markers = L.markerClusterGroup();

function getIcon(tipo){
    return L.icon({
        iconUrl: 'icons/' + tipo.toLowerCase() + '.png', 
        iconSize: [32, 32],
        iconAnchor: [16, 32],
        popupAnchor: [0, -32]
    });
}

<?php foreach($incidencias as $inc): ?>
var marker = L.marker(
    [<?= $inc['lat'] ?: '0' ?>, <?= $inc['lng'] ?: '0' ?>],
    { icon: getIcon("<?= addslashes($inc['tipo_nombre']) ?>") }
);
marker.bindPopup(`<strong><?= addslashes($inc['titulo']) ?></strong><br>
Tipo: <?= addslashes($inc['tipo_nombre']) ?><br>
Provincia: <?= addslashes($inc['provincia_nombre']) ?><br>
Municipio: <?= addslashes($inc['municipio_nombre']) ?><br>
Muertos: <?= $inc['muertos'] ?>, Heridos: <?= $inc['heridos'] ?><br>
<button class="btn btn-sm btn-primary" onclick="abrirModal(<?= $inc['id'] ?>)">Ver detalle</button>`);
markers.addLayer(marker);
<?php endforeach; ?>

mapa.addLayer(markers);

function abrirModal(id){
    fetch('detalle_incidencia.php?id='+id)
        .then(res=>res.text())
        .then(html=>{
            document.getElementById('modal-body-content').innerHTML = html;
            var modal = new bootstrap.Modal(document.getElementById('detalleModal'));
            modal.show();
        });
}
</script>
