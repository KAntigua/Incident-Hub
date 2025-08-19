<?php
session_start();
require_once '../config.php';
require_once '../plantillas/plantillarep.php';
$plantilla = PlantillaRep::aplicar();


$tipos_incidencias = $pdo->query("SELECT id, nombre FROM tipos_incidencias ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
$provincias = $pdo->query("SELECT id, nombre FROM provincias ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
$municipios = $pdo->query("SELECT id, nombre, provincia_id FROM municipios ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
$barrios = $pdo->query("SELECT id, nombre, municipio_id FROM barrios ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);


$errores = [];
$datos = [
    'titulo' => '',
    'descripcion' => '',
    'tipo' => '',
    'provincia' => '',
    'municipio' => '',
    'barrio' => '',
    'latitud' => '',
    'longitud' => '',
    'muertos' => 0,
    'heridos' => 0,
    'perdida' => 0,
    'link' => '',
    'foto' => '',
    'fecha' => ''
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    
    foreach($datos as $key => &$value){
        $value = $_POST[$key] ?? '';
    }

   
    if(empty($datos['titulo'])) $errores[] = "El título no puede estar vacío.";
    if(empty($datos['descripcion'])) $errores[] = "La descripción no puede estar vacía.";
    if(empty($datos['tipo'])) $errores[] = "Debe seleccionar un tipo de incidencia.";
    if(empty($datos['provincia'])) $errores[] = "Debe seleccionar una provincia.";
    if(empty($datos['municipio'])) $errores[] = "Debe seleccionar un municipio.";
    if(empty($datos['barrio'])) $errores[] = "Debe seleccionar un barrio.";
    if(empty($datos['latitud']) || empty($datos['longitud'])) $errores[] = "Las coordenadas no pueden estar vacías.";
    if(empty($datos['fecha'])) $errores[] = "Debe ingresar la fecha de ocurrencia.";

    if(empty($errores)){
        
        $stmt = $pdo->prepare("INSERT INTO incidencias 
            (titulo, descripcion, tipo_id, provincia_id, municipio_id, barrio_id, lat, lng, muertos, heridos, perdida, link_social, foto, reportero_id, fecha_ocurrencia, fecha_creacion, validada) 
            VALUES 
            (:titulo, :descripcion, :tipo_id, :provincia_id, :municipio_id, :barrio_id, :lat, :lng, :muertos, :heridos, :perdida, :link_social, :foto, :reportero_id, :fecha_ocurrencia, :fecha_creacion, 0)"); // validada = 0 = pendiente

        $stmt->execute([
            ':titulo' => $datos['titulo'],
            ':descripcion' => $datos['descripcion'],
            ':tipo_id' => $datos['tipo'],
            ':provincia_id' => $datos['provincia'],
            ':municipio_id' => $datos['municipio'],
            ':barrio_id' => $datos['barrio'],
            ':lat' => $datos['latitud'],
            ':lng' => $datos['longitud'],
            ':muertos' => $datos['muertos'],
            ':heridos' => $datos['heridos'],
            ':perdida' => $datos['perdida'],
            ':link_social' => $datos['link'],
            ':foto' => $datos['foto'],
            ':reportero_id' => $_SESSION['user_id'], 
            ':fecha_ocurrencia' => $datos['fecha'],
            ':fecha_creacion' => date('Y-m-d H:i:s')
        ]);

        
        header("Location: panel.php");
        exit();
    }
}
?>

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

.custom-btn { b
    ackground-color:#9c710bff; 
    border:none;
     border-radius:30px; 
    padding:6px 16px; color:#fff !important; 
    font-weight:bold; 
    transition: all 0.3s ease; 
}

.custom-btn:hover { 
    background-color:#6d4f07ff; 
    transform:scale(1.05); 
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

label {
     color:#fff; 
    }

input, select, textarea {
     background: rgba(255,255,255,0.2); 
     border:none; 
     color:#fff; 
    }

input::placeholder, textarea::placeholder {
     color:#f1f1f1;
     }

.error-msg { 
    background-color:#ff4c4c;
     color:#fff; 
     padding:10px; 
     border-radius:10px; 
     margin-bottom:15px; 
     }
</style>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="glass-card p-4">
                <h3 class="custom-title mb-4">Registro de Incidencias</h3>

                <?php if(!empty($errores)): ?>
                    <div class="error-msg">
                        <ul>
                            <?php foreach($errores as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label>Fecha de ocurrencia</label>
                        <input type="date" name="fecha" class="form-control" required value="<?= htmlspecialchars($datos['fecha']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Título</label>
                        <input type="text" name="titulo" class="form-control" required value="<?= htmlspecialchars($datos['titulo']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Tipo de incidencia</label>
                        <select name="tipo" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <?php foreach($tipos_incidencias as $tipo): ?>
                                <option value="<?= $tipo['id'] ?>" <?= $tipo['id'] == $datos['tipo'] ? 'selected' : '' ?>><?= $tipo['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="3" required><?= htmlspecialchars($datos['descripcion']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label>Provincia</label>
                        <select name="provincia" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <?php foreach($provincias as $prov): ?>
                                <option value="<?= $prov['id'] ?>" <?= $prov['id'] == $datos['provincia'] ? 'selected' : '' ?>><?= $prov['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Municipio</label>
                        <select name="municipio" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <?php foreach($municipios as $mun): ?>
                                <option value="<?= $mun['id'] ?>" <?= $mun['id'] == $datos['municipio'] ? 'selected' : '' ?>><?= $mun['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Barrio</label>
                        <select name="barrio" class="form-control" required>
                            <option value="">Seleccione...</option>
                            <?php foreach($barrios as $barrio): ?>
                                <option value="<?= $barrio['id'] ?>" <?= $barrio['id'] == $datos['barrio'] ? 'selected' : '' ?>><?= $barrio['nombre'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Coordenadas</label>
                        <div class="d-flex gap-2">
                            <input type="text" name="latitud" placeholder="Latitud" class="form-control" required value="<?= htmlspecialchars($datos['latitud']) ?>">
                            <input type="text" name="longitud" placeholder="Longitud" class="form-control" required value="<?= htmlspecialchars($datos['longitud']) ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label>Muertos</label>
                        <input type="number" name="muertos" class="form-control" min="0" value="<?= $datos['muertos'] ?>">
                    </div>
                    <div class="mb-3">
                        <label>Heridos</label>
                        <input type="number" name="heridos" class="form-control" min="0" value="<?= $datos['heridos'] ?>">
                    </div>
                    <div class="mb-3">
                        <label>Pérdida estimada en RD$</label>
                        <input type="number" name="perdida" class="form-control" min="0" value="<?= $datos['perdida'] ?>">
                    </div>
                    <div class="mb-3">
                        <label>Link a redes sociales</label>
                        <input type="url" name="link" class="form-control" value="<?= htmlspecialchars($datos['link']) ?>">
                    </div>
                    <div class="mb-3">
                        <label>Foto del hecho (URL)</label>
                        <input type="url" name="foto" class="form-control" value="<?= htmlspecialchars($datos['foto']) ?>">
                    </div>
                    <button type="submit" class="custom-btn">Enviar para Validación</button>
                </form>
                
            </div>
        </div>
    </div>
</div>
