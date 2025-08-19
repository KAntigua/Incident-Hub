<?php
require_once '../../config.php';
require_once '../../plantillas/plantillaval.php';
$plantilla = PlantillaVal::aplicar();
?>

<div class="container mt-4">
    <div class="card-body card p-4 mb-4 rounded shadow text-center hero-card">
        <h2 class="fw-bold text-white">📚 Panel de Catálogos</h2>
        <p class="mb-0 text-light">Gestiona provincias, municipios, barrios y tipos de incidencias.</p>
    </div>

    <div class="row g-3 mt-4">
        <div class="col-md-6 col-lg-3">
            <div class="card glass-card shadow-sm border-0 h-100 hover-scale">
                <div class="card-body text-center">
                    <i class="bi bi-geo-alt-fill display-3 icon-style"></i>
                    <h5 class="card-title fw-bold">Provincias</h5>
                    <p class="card-text">Administra el catálogo de provincias.</p>
                    <a href="provincias/ver.php" class="btn custom-btn btn-sm">Ir a Provincias</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card glass-card shadow-sm border-0 h-100 hover-scale">
                <div class="card-body text-center">
                    <i class="bi bi-building display-3 icon-style"></i>
                    <h5 class="card-title fw-bold">Municipios</h5>
                    <p class="card-text">Gestiona municipios vinculados a provincias.</p>
                    <a href="municipios.php" class="btn custom-btn btn-sm">Ir a Municipios</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card glass-card shadow-sm border-0 h-100 hover-scale">
                <div class="card-body text-center">
                    <i class="bi bi-house-door-fill display-3 icon-style"></i>
                    <h5 class="card-title fw-bold">Barrios</h5>
                    <p class="card-text">Administra los barrios de cada municipio.</p>
                    <a href="barrios.php" class="btn custom-btn btn-sm">Ir a Barrios</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3">
            <div class="card glass-card shadow-sm border-0 h-100 hover-scale">
                <div class="card-body text-center">
                    <i class="bi bi-exclamation-triangle-fill display-3 icon-style"></i>
                    <h5 class="card-title fw-bold">Tipos de Incidencias</h5>
                    <p class="card-text">Define y gestiona los tipos de incidencias.</p>
                    <a href="tipos_incidencias.php" class="btn custom-btn btn-sm">Ir a Incidencias</a>
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
.icon-style:hover {
    transform: scale(1.15);
    color: #ffd45c;
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
