<?php
class PlantillaRep {
    public static $instancia = null;

    public static function aplicar(): PlantillaRep {
        if (self::$instancia == null) {
            self::$instancia = new PlantillaRep();
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
            <title>Incident Hub</title>

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
                    background: rgba(174, 30, 30, 0.8); 
                    backdrop-filter: blur(5px);
                    -webkit-backdrop-filter: blur(5px);
                }

                .navbar-incident .navbar-brand,
                .navbar-incident .nav-link {
                    color: #fff !important;
                    font-weight: 500;
                }
            </style>
        </head>
        <body>

        <nav class="navbar navbar-expand-lg navbar-dark navbar-incident mb-4">
            <div class="container">
                <a class="navbar-brand fw-bold" href="../../../../Incident-Hub/index.php">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Incident Hub
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarIncident">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarIncident">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item"><a class="nav-link" href="../../../../Incident-Hub/reportero/panel.php">Inicio</a></li>
                        <li class="nav-item"><a class="nav-link" href="../../../../Incident-Hub/reportero/registroincidencia.php">Registro de incidencias</a></li>
                        <li class="nav-item"><a class="nav-link" href="../../../../Incident-Hub/reportero/misreportes.php">Mis Reportes</a></li>
                        <li class="nav-item"><a class="nav-link" href="../../../../Incident-Hub/reportero/vistaalterna.php">Vista alterna</a></li>
                        <li class="nav-item"><a class="nav-link" href="../../../../Incident-Hub/reportero/exportaruno.php">Exportar Reportes</a></li>
                        <li class="nav-item">
                            <a class="nav-link text-warning fw-bold" 
                               href="../../../../Incident-Hub/index.php" 
                               onclick="return confirm('¿Seguro que deseas cerrar sesión?');">Cerrar sesión
                            </a>
                        </li>
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
