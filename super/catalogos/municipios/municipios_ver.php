<?php
require_once '../../../config.php';
require_once '../../../plantillas/plantillaval.php';
$plantilla = PlantillaVal::aplicar();

// Traer municipios con su provincia
$stmt = $pdo->query("
    SELECT m.id, m.nombre, p.nombre AS provincia 
    FROM municipios m
    LEFT JOIN provincias p ON m.provincia_id = p.id
    ORDER BY m.id
");
$municipios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traer provincias para el select en el form si quieres agregar inline
$stmt2 = $pdo->query("SELECT id, nombre FROM provincias ORDER BY nombre");
$provincias = $stmt2->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container mt-4">
    <div class="card-body card p-4 mb-4 rounded shadow text-center hero-card">
        <h2 class="fw-bold text-white">Catálogo de Municipios</h2>
        <p class="mb-0 text-light">Administra los municipios registrados en el sistema.</p>
    </div>

    <div class="mb-4 text-end">
        <a href="municipios_form.php" class="btn custom-btn px-4 py-2">+ Agregar Municipio</a>
    </div>

    <div class="card glass-card shadow-sm border-0 p-4">
        <div class="table-responsive">
            <table class="table-fixed w-full">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="w-1/12 px-4 py-2">ID</th>
                        <th class="
                                                </th>
                        <th class="w-1/3 px-4 py-2">Municipio</th>
                        <th class="w-1/3 px-4 py-2">Provincia</th>
                        <th class="w-1/6 px-4 py-2">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($municipios as $row): ?>
                        <tr>
                            <td class="px-4 py-2 align-middle"><?= $row['id'] ?></td>
                            <td class="px-4 py-2 align-middle"><?= htmlspecialchars($row['nombre']) ?></td>
                            <td class="px-4 py-2 align-middle"><?= htmlspecialchars($row['provincia']) ?></td>
                            <td class="px-4 py-2 align-middle">
                                <a href="municipios_form.php?id=<?= $row['id'] ?>" class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Editar</a>
                                <a href="municipios_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('¿Seguro que deseas eliminar?')" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4 text-center">
        <a href="../panel.php" class="btn custom-btn px-4 py-2">⬅ Volver Atrás</a>
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
</style>

