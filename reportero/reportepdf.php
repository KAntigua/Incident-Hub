<?php
require_once '../config.php';
require('fpdf182/fpdf.php'); 


$where = [];
$params = [];

if(!empty($_GET['provincia'])){
    $where[] = "i.provincia_id = ?";
    $params[] = $_GET['provincia'];
}
if(!empty($_GET['tipo'])){
    $where[] = "i.tipo_id = ?";
    $params[] = $_GET['tipo'];
}
if(!empty($_GET['fecha_desde'])){
    $where[] = "i.fecha_ocurrencia >= ?";
    $params[] = $_GET['fecha_desde'];
}
if(!empty($_GET['fecha_hasta'])){
    $where[] = "i.fecha_ocurrencia <= ?";
    $params[] = $_GET['fecha_hasta'];
}

$sql = "
    SELECT i.id, i.titulo, i.descripcion, ti.nombre AS tipo, 
           p.nombre AS provincia, m.nombre AS municipio, b.nombre AS barrio,
           i.fecha_ocurrencia, i.muertos, i.heridos, i.perdida
    FROM incidencias i
    LEFT JOIN tipos_incidencias ti ON i.tipo_id = ti.id
    LEFT JOIN provincias p ON i.provincia_id = p.id
    LEFT JOIN municipios m ON i.municipio_id = m.id
    LEFT JOIN barrios b ON i.barrio_id = b.id
";

if(count($where) > 0){
    $sql .= " WHERE " . implode(" AND ", $where);
}

$query = $pdo->prepare($sql);
$query->execute($params);
$incidencias = $query->fetchAll(PDO::FETCH_ASSOC);


ob_clean();

$pdf = new FPDF('L','mm','A4'); 
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);


$pdf->Cell(0,10,'Reporte de Incidencias',0,1,'C');
$pdf->Ln(3);


$pdf->SetFont('Arial','B',10);
$headers = ['ID','Titulo','Descripcion','Tipo','Provincia','Municipio','Barrio','Fecha','Muertos','Heridos','Perdida'];
$widths = [10,35,50,25,25,25,25,25,15,15,20]; 

foreach($headers as $i => $col){
    $pdf->Cell($widths[$i],7,$col,1,0,'C');
}
$pdf->Ln();


$pdf->SetFont('Arial','',9);
foreach($incidencias as $row){
    $pdf->Cell($widths[0],6,$row['id'],1);
    $pdf->Cell($widths[1],6,substr($row['titulo'],0,20),1);
    $pdf->Cell($widths[2],6,substr($row['descripcion'],0,20),1);
    $pdf->Cell($widths[3],6,substr($row['tipo'],0,15),1);
    $pdf->Cell($widths[4],6,substr($row['provincia'],0,15),1);
    $pdf->Cell($widths[5],6,substr($row['municipio'],0,15),1);
    $pdf->Cell($widths[6],6,substr($row['barrio'],0,15),1);
    $pdf->Cell($widths[7],6,$row['fecha_ocurrencia'],1);
    $pdf->Cell($widths[8],6,$row['muertos'],1);
    $pdf->Cell($widths[9],6,$row['heridos'],1);
    $pdf->Cell($widths[10],6,$row['perdida'],1);
    $pdf->Ln();
}


$pdf->Output('D','reporte_incidencias.pdf');
exit;
