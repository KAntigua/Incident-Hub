<?php
require_once '../config.php';
require_once '../plantillas/plantillaval.php';
$plantilla = PlantillaVal::aplicar();
?>

<div class="container mt-4">
    <div class="card-body card p-4 mb-4 rounded shadow text-center hero-card">
        <h2 class="fw-bold text-white">¡Bienvenido, Validador!</h2>
        <p class="mb-0 text-light">Administra el sistema y revisa reportes de forma rápida y sencilla.</p>
    </div>
    
    <div class="row g-3 mt-4">
        <div class="col-md-6 col-lg-3">
            <div class="card glass-card shadow-sm border-0 h-100 hover-scale">
                <div class="card-body text-center">
                    <i class="bi bi-book-fill display-3 icon-style"></i>
                    <h5 class="card-title fw-bold">Catálogos</h5>
                    <p class="card-text">Ingresar y gestionar provincias, municipios, barrios y tipos de incidencias.</p>
                    <a href="../../Incident-Hub/super/catalogos/panel.php" class="btn custom-btn btn-sm">Ir a Catálogos</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card glass-card shadow-sm border-0 h-100 hover-scale">
                <div class="card-body text-center">
                    <i class="bi bi-check2-circle display-3 icon-style"></i>
                    <h5 class="card-title fw-bold">Validaciones</h5>
                    <p class="card-text">Revisar y aprobar reportes y correcciones enviadas por los reporteros.</p>
                    <a href="../../Incident-Hub/super/validaciones/validaciones.php" class="btn custom-btn btn-sm">Ir a Validaciones</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card glass-card shadow-sm border-0 h-100 hover-scale">
                <div class="card-body text-center">
                    <i class="bi bi-arrows-collapse display-3 icon-style"></i>
                    <h5 class="card-title fw-bold">Fusionar</h5>
                    <p class="card-text">Unir múltiples reportes similares en uno solo de forma eficiente.</p>
                    <a href="../../Incident-Hub/admin/fusionar.php" class="btn custom-btn btn-sm">Ir a Fusionar</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card glass-card shadow-sm border-0 h-100 hover-scale">
                <div class="card-body text-center">
                    <i class="bi bi-bar-chart-line-fill display-3 icon-style"></i>
                    <h5 class="card-title fw-bold">Estadísticas</h5>
                    <p class="card-text">Visualizar gráficos dinámicos por tipo de incidencia y análisis general.</p>
                    <a href="../../Incident-Hub/admin/estadisticas.php" class="btn custom-btn btn-sm">Ver Estadísticas</a>
                </div>
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

.glass-card {
    background: rgba(255, 255, 255, 0.1);
    border-radius: 16px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    color: #fff;
    transition: all 0.3s ease-in-out;
}
.glass-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.3), 0 0 15px rgba(255,255,255,0.2);
}

.icon-style {
    color: #ffb400;
    margin-bottom: 15px;
    transition: transform 0.3s ease, color 0.3s ease;
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

.card-body h5,
.card-body p {
    color: #f1f1f1;
}
</style>

<?php
?>
