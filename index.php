<?php
require_once 'plantillas/plantilla1.php';
$plantilla = Plantilla1::aplicar();
?>

<div class="hero">
    <div class="overlay"></div>
    <div class="container-incident">
        <h1>Incident Hub</h1>
        <p>
            Bienvenido a <strong>Incident Hub</strong>, la plataforma para registrar,
            visualizar y gestionar incidencias ocurridas en el país.
            Selecciona tu rol para continuar.
        </p>
        <a href="login_validador.php" class="btn btn-validador">Ingresar como Validador</a>
        <a href="login_reportero.php" class="btn btn-reportero">Ingresar como Reportero</a>
    </div>
</div>

<style>
    body {
        margin: 0;
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), 
                    url('img/isaac-n-V1kk3KTBiEk-unsplash.jpg') no-repeat center center/cover;
        color: #fff;
    }

    .hero {
        position: relative;
        min-height: calc(100vh - 120px); 
        display: flex;
        justify-content: center;
        align-items: center;
        text-align: center;
    }

    .container-incident {
        position: relative;
        z-index: 1;
        background: rgba(255,255,255,0.1);
        padding: 40px;
        border-radius: 12px;
        backdrop-filter: blur(8px);
        max-width: 450px;
        width: 90%;
    }

    h1 {
        font-size: 2.2em;
        margin-bottom: 15px;
        font-weight: 600;
    }

    p {
        font-size: 1em;
        margin-bottom: 25px;
        line-height: 1.5;
    }

    .btn {
        display: inline-block;
        padding: 12px 20px;
        margin: 8px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .btn-validador {
        background: #ff9800;
        color: #fff;
    }

    .btn-reportero {
        background: #be2f2f;
        color: #fff;
    }

    .btn:hover {
        opacity: 0.9;
        transform: translateY(-2px);
    }
</style>
