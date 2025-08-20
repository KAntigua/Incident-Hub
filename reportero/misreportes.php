<?php
session_start();
require_once '../config.php';
require_once '../plantillas/plantillarep.php';
$plantilla = PlantillaRep::aplicar();

if (!isset($_SESSION['reportero_id'])) {
    die("Debes iniciar sesión para ver tus reportes.");
}

$reportero_id = $_SESSION['reportero_id'];

$stmt = $pdo->prepare("SELECT id, titulo, fecha_ocurrencia, fecha_creacion, validada 
                       FROM incidencias 
                       WHERE reportero_id = ?
                       ORDER BY fecha_creacion DESC");
$stmt->execute([$reportero_id]);
$reportes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-5">
        <div class="card-body card p-4 mb-4 rounded shadow text-center hero-card">
    <h2 class="fw-bold text-white"> Mis Reportes</h2>
</div>
    <div class="card glass-card shadow-sm border-0 p-4">
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-center align-middle">
                <table class="table-fixed w-full">
            <thead>
                    <tr>
                        <th>ID</th>
                        <th>Título</th>
                        <th>Fecha Ocurrencia</th>
                        <th>Fecha Creación</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($reportes) > 0): ?>
                        <?php foreach ($reportes as $r): ?>
                            <tr>
                                <td><?= htmlspecialchars($r['id']) ?></td>
                                <td><?= htmlspecialchars($r['titulo']) ?></td>
                                <td><?= htmlspecialchars($r['fecha_ocurrencia']) ?></td>
                                <td><?= htmlspecialchars($r['fecha_creacion']) ?></td>
                                <td>
                                    <?php
                                    if ($r['validada'] == 0) {
                                        echo "<span class='badge bg-warning text-dark'>Pendiente ⏳</span>";
                                    } elseif ($r['validada'] == 1) {
                                        echo "<span class='badge bg-success'>Validado ✅</span>";
                                    } elseif ($r['validada'] == 2) {
                                        echo "<span class='badge bg-danger'>Rechazado ❌</span>";
                                    }
                                    ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">No tienes reportes aún.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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

 <div class="mt-4 text-center">
        <a href="../reportero/panel.php" class="btn custom-btn px-4 py-2">⬅ Volver Atrás</a>
    </div>
</div>