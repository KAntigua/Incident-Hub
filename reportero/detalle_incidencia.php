<?php
session_start();
require_once '../config.php';
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "<p>Incidencia no encontrada.</p>";
    exit;
}

$stmt = $pdo->prepare("SELECT i.*, t.nombre AS tipo_nombre, p.nombre AS provincia_nombre, 
                              m.nombre AS municipio_nombre, b.nombre AS barrio_nombre, u.nombre AS reportero
                       FROM incidencias i
                       LEFT JOIN tipos_incidencias t ON i.tipo_id = t.id
                       LEFT JOIN provincias p ON i.provincia_id = p.id
                       LEFT JOIN municipios m ON i.municipio_id = m.id
                       LEFT JOIN barrios b ON i.barrio_id = b.id
                       LEFT JOIN usuarios u ON i.reportero_id = u.id
                       WHERE i.id = :id LIMIT 1");
$stmt->execute([':id'=>$id]);
$inc = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$inc) {
    echo "<p>Incidencia no encontrada.</p>";
    exit;
}

$comentariosStmt = $pdo->prepare("SELECT c.*, u.nombre AS usuario_nombre
                                  FROM comentarios c
                                  JOIN usuarios u ON c.usuario_id = u.id
                                  WHERE c.incidencia_id = :id
                                  ORDER BY c.fecha ASC");
$comentariosStmt->execute([':id'=>$id]);
$comentarios = $comentariosStmt->fetchAll(PDO::FETCH_ASSOC);

$provincias = $pdo->query("SELECT * FROM provincias")->fetchAll(PDO::FETCH_ASSOC);
$municipios = $pdo->query("SELECT * FROM municipios")->fetchAll(PDO::FETCH_ASSOC);
$tipos = $pdo->query("SELECT * FROM tipos_incidencias")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-3">
    <h5><?= htmlspecialchars($inc['titulo']) ?></h5>
    <p><strong>Tipo:</strong> <?= htmlspecialchars($inc['tipo_nombre']) ?></p>
    <p><strong>Provincia:</strong> <?= htmlspecialchars($inc['provincia_nombre']) ?> | 
       <strong>Municipio:</strong> <?= htmlspecialchars($inc['municipio_nombre']) ?> | 
       <strong>Barrio:</strong> <?= htmlspecialchars($inc['barrio_nombre']) ?></p>
    <p><strong>Muertos:</strong> <?= $inc['muertos'] ?> | 
       <strong>Heridos:</strong> <?= $inc['heridos'] ?> | 
       <strong>Pérdida estimada:</strong> RD$ <?= $inc['perdida'] ?></p>
    <p><strong>Reportero:</strong> <?= htmlspecialchars($inc['reportero']) ?></p>
    <?php if($inc['foto']): ?>
        <img src="<?= htmlspecialchars($inc['foto']) ?>" alt="Foto" class="img-fluid mb-2"/>
    <?php endif; ?>
    <?php if($inc['link_social']): ?>
        <p><a href="<?= htmlspecialchars($inc['link_social']) ?>" target="_blank">Ver en redes sociales</a></p>
    <?php endif; ?>
    <p><?= nl2br(htmlspecialchars($inc['descripcion'])) ?></p>
</div>

<hr>
<h6>Comentarios</h6>

<div class="mb-3" style="max-height:250px; overflow-y:auto;">
    <?php if(empty($comentarios)): ?>
        <div class="alert alert-secondary">Aún no hay comentarios.</div>
    <?php else: ?>
        <?php foreach($comentarios as $c): ?>
            <div class="border rounded p-2 mb-2">
                <strong><?= htmlspecialchars($c['usuario_nombre']) ?></strong> 
                <small class="text-muted"><?= $c['fecha'] ?></small>
                <p class="mb-0"><?= nl2br(htmlspecialchars($c['comentario'])) ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<?php if(isset($_SESSION['reportero_id'])): ?>
    <div class="text-end">
        <button class="btn btn-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#formComentario">
            + Agregar Comentario
        </button>
    </div>

    <div id="formComentario" class="collapse mt-2">
        <form method="POST" action="agregar_comentario.php">
            <input type="hidden" name="incidencia_id" value="<?= $inc['id'] ?>">
            <textarea name="comentario" class="form-control text-white bg-dark" placeholder="Escribe tu comentario..." required></textarea>
            <button class="btn btn-success btn-sm">Enviar</button>
        </form>
    </div>
<?php endif; ?>

<hr>
<h6>Sugerir Correcciones</h6>
<?php if(isset($_SESSION['reportero_id'])): ?>
<form method="POST" action="sugerir_correccion.php">
    <input type="hidden" name="incidencia_id" value="<?= $inc['id'] ?>">
    <div class="row">
        <div class="col-md-3 mb-2">
            <label style="color:#000;">Muertos</label>
            <input type="number" name="muertos" value="<?= $inc['muertos'] ?>" 
                   class="form-control" style="color:#000; border: 1px solid #000;">
        </div>
        <div class="col-md-3 mb-2">
            <label style="color:#000;">Heridos</label>
            <input type="number" name="heridos" value="<?= $inc['heridos'] ?>" 
                   class="form-control" style="color:#000; border: 1px solid #000;">
        </div>
        <div class="col-md-3 mb-2">
            <label style="color:#000;">Provincia</label>
            <select name="provincia_id" class="form-select" style="color:#000; border:1px solid #000;">
                <option value="">--Seleccionar--</option>
                <?php foreach($provincias as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= $p['id']==$inc['provincia_id']?'selected':'' ?>><?= htmlspecialchars($p['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 mb-2">
            <label style="color:#000;">Municipio</label>
            <select name="municipio_id" class="form-select" style="color:#000; border:1px solid #000;">
                <option value="">--Seleccionar--</option>
                <?php foreach($municipios as $m): ?>
                    <option value="<?= $m['id'] ?>" <?= $m['id']==$inc['municipio_id']?'selected':'' ?>><?= htmlspecialchars($m['nombre']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3 mb-2">
            <label style="color:#000;">Latitud</label>
            <input type="text" name="latitud" value="<?= $inc['lat'] ?>" 
                   class="form-control" style="color:#000; border:1px solid #000;">
        </div>
        <div class="col-md-3 mb-2">
            <label style="color:#000;">Longitud</label>
            <input type="text" name="longitud" value="<?= $inc['lng'] ?>" 
                   class="form-control" style="color:#000; border:1px solid #000;">
        </div>
        <div class="col-md-3 mb-2">
            <label style="color:#000;">Pérdida estimada</label>
            <input type="number" step="0.01" name="perdida_estimado" value="<?= $inc['perdida'] ?>" 
                   class="form-control" style="color:#000; border:1px solid #000;">
        </div>
    </div>
    <button class="btn btn-warning btn-sm mt-2">Sugerir Corrección</button>
</form>
<?php endif; ?>

