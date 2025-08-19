<?php
class PlantillaVal {
    public static $instancia = null;

    public static function aplicar(): PlantillaVal {
        if (self::$instancia == null) {
            self::$instancia = new PlantillaVal();
        }
        return self::$instancia;
    }

    public function __construct() {
        ?>
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Incident Hub - Validador</title>

            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

            <style>
                body {
                    font-family: 'Poppins', sans-serif;
                    margin: 0;
                    padding: 0;
                    min-height: 100vh;
                    background: #f4f6f9;
                    color: #333;
                }

                .navbar-incident {
                    background: rgba(174, 30, 30, 0.7); 
                    backdrop-filter: blur(5px);
                    -webkit-backdrop-filter: blur(5px);
                }

                .navbar-incident .navbar-brand,
                .navbar-incident .nav-link {
                    color: #fff !important;
                    font-weight: 500;
                }

                .nav-tabs .nav-link.active {
                    background-color: #ae1e1e;
                    color: #fff;
                }
            </style>
        </head>
        <body>

        <nav class="navbar navbar-expand-lg navbar-dark navbar-incident mb-4">
            <div class="container">
                <a class="navbar-brand fw-bold" href="../../../../Incident-Hub/super/index.php">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Incident Hub
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarIncident">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarIncident">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="../../../../Incident-Hub/super/catalogos/panel.php">Catálogos</a></li>
                        <li class="nav-item"><a class="nav-link" href="../../Incident-Hub/admin/validaciones.php">Validaciones</a></li>
                        <li class="nav-item"><a class="nav-link" href="../../Incident-Hub/admin/fusionar.php">Fusionar</a></li>
                        <li class="nav-item"><a class="nav-link" href="../../Incident-Hub/admin/estadisticas.php">Estadísticas</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container">
        <?php
    }

    public function __destruct() {
        ?>
        </div> 
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
        </body>
        </html>
        <?php
    }
}
