<?php
require_once '../../config.php';
require_once '../../plantillas/plantillaval.php';
$plantilla = PlantillaVal::aplicar();

$queryTipos = $pdo->query("
    SELECT ti.nombre AS tipo, COUNT(i.id) AS total
    FROM tipos_incidencias ti
    LEFT JOIN incidencias i ON i.tipo_id = ti.id
    GROUP BY ti.id, ti.nombre
")->fetchAll(PDO::FETCH_ASSOC);

$tipos = [];
$totales = [];
foreach ($queryTipos as $row) {
    $tipos[] = $row['tipo'];
    $totales[] = $row['total'];
}


$queryFechas = $pdo->query("
    SELECT DATE(fecha_ocurrencia) AS fecha, COUNT(id) AS total
    FROM incidencias
    GROUP BY DATE(fecha_ocurrencia)
    ORDER BY fecha ASC
")->fetchAll(PDO::FETCH_ASSOC);

$fechas = [];
$totalesFechas = [];
foreach ($queryFechas as $row) {
    $fechas[] = $row['fecha'];
    $totalesFechas[] = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Estadísticas de Incidencias</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
body {
    margin: 0;
    font-family: 'Poppins', sans-serif;
    background-color: #562b2bff;
    min-height: 100vh;
}

.container {
    display: grid;
    grid-template-columns: repeat(2, 1fr); 
    gap: 20px;
    max-width: 1000px;
    margin: 20px auto;
}

.glass-card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    color: #fff;
    transition: all 0.3s ease-in-out;
    padding: 15px;
    border-radius: 15px;
}

.glass-card.full-width {
    grid-column: span 2; 
}

.glass-card:hover {
    box-shadow: 0 15px 30px rgba(0,0,0,0.3), 0 0 15px rgba(255,255,255,0.2);
}

.glass-card.full-width {
    grid-column: span 2;
    text-align: center; 
}

.glass-card.full-width canvas {
    width: 100% !important;  
    max-width: 900px;      
    margin: 0 auto;        
    display: block;
}


h2.chart-title {
    text-align: center;
    margin-bottom: 10px;
}

canvas {
    background-color: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
    padding: 5px;
    height: 300px !important;
}

h2.chart-title {
    background-color: #9c710bff; 
    color: #fff;               
    padding: 10px 15px;
    border-radius: 10px;
    text-align: center;
    margin: 0 0 15px 0;    
    font-weight: bold;
    font-size: 1.2rem;
    box-shadow: 0 3px 6px rgba(0,0,0,0.2);
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

</style>
</head>
<body>

<div class="container">
    <div class="glass-card">
        <h2 class="chart-title">Cantidad de Incidencias por Tipo</h2>
        <canvas id="barChart"></canvas>
    </div>

    <div class="glass-card">
        <h2 class="chart-title">Porcentaje de Incidencias por Tipo</h2>
        <canvas id="pieChart"></canvas>
    </div>

    <div class="glass-card full-width">
        <h2 class="chart-title">Incidencias a lo Largo del Tiempo</h2>
        <canvas id="lineChart"></canvas>
    </div>
    <div class="mt-4 text">
    <a href="../index.php" class="btn custom-btn px-4 py-2">⬅ Volver Atrás</a>
</div>

</div>

<script>
const colors = [
    'rgba(156, 113, 11, 0.7)',
    'rgba(101, 58, 20, 0.7)',
    'rgba(175, 103, 25, 0.7)',
    'rgba(200, 150, 50, 0.7)',
    'rgba(220, 180, 80, 0.7)',
    'rgba(255, 215, 0, 0.7)',
    'rgba(139, 69, 19, 0.7)'
];

const barCtx = document.getElementById('barChart').getContext('2d');
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($tipos); ?>,
        datasets: [{
            label: 'Cantidad de incidencias',
            data: <?php echo json_encode($totales); ?>,
            backgroundColor: colors,
            borderColor: colors.map(c => c.replace('0.7','1')),
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true, ticks: { color: '#fff' } },
            x: { ticks: { color: '#fff' } }
        }
    }
});

const pieCtx = document.getElementById('pieChart').getContext('2d');
new Chart(pieCtx, {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($tipos); ?>,
        datasets: [{
            data: <?php echo json_encode($totales); ?>,
            backgroundColor: colors
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { color: '#fff' } } }
    }
});

const lineCtx = document.getElementById('lineChart').getContext('2d');
new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($fechas); ?>,
        datasets: [{
            label: 'Incidencias',
            data: <?php echo json_encode($totalesFechas); ?>,
            fill: true,
            backgroundColor: 'rgba(156, 113, 11, 0.2)',
            borderColor: 'rgba(156, 113, 11, 1)',
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { labels: { color: '#fff' } } },
        scales: {
            y: { beginAtZero: true, ticks: { color: '#fff' } },
            x: { ticks: { color: '#fff' } }
        }
    }
});
</script>

</body>
</html>
