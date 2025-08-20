<?php
require_once '../config.php';
require_once '../reportero/fpdf182/fpdf.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID de reporte no especificado.");
}

$id = intval($_GET['id']);


$stmt = $pdo->prepare("SELECT i.*, 
                              t.nombre AS tipo, 
                              p.nombre AS provincia, 
                              m.nombre AS municipio, 
                              b.nombre AS barrio
                       FROM incidencias i
                       JOIN tipos_incidencias t ON i.tipo_id = t.id
                       JOIN provincias p ON i.provincia_id = p.id
                       JOIN municipios m ON i.municipio_id = m.id
                       JOIN barrios b ON i.barrio_id = b.id
                       WHERE i.id = ? AND i.validada = 1");
$stmt->execute([$id]);
$reporte = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$reporte) {
    die("No se encontró el reporte o no está validado.");
}


$pdf = new FPDF();
$pdf->AddPage();


$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Reporte de Incidencia',0,1,'C');
$pdf->Ln(5);


$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Titulo:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->MultiCell(0,10,utf8_decode($reporte['titulo']),0,'L');


$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Descripcion:',0,1);
$pdf->SetFont('Arial','',12);
$pdf->MultiCell(0,8,utf8_decode($reporte['descripcion']),0,'L');


$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Tipo:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,utf8_decode($reporte['tipo']),0,1);


$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Provincia:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,utf8_decode($reporte['provincia']),0,1);


$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Municipio:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,utf8_decode($reporte['municipio']),0,1);


$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Barrio:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,utf8_decode($reporte['barrio']),0,1);


$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Latitud:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,$reporte['lat'],0,1);

$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Longitud:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,$reporte['lng'],0,1);


$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Muertos:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,$reporte['muertos'],0,1);


$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Heridos:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,$reporte['heridos'],0,1);


$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Perdida estimada:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,$reporte['perdida'],0,1);


$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Link Social:',0,1);
$pdf->SetFont('Arial','U',12);
$pdf->SetTextColor(0,0,255);
$pdf->MultiCell(0,8,$reporte['link_social'],0,'L');
$pdf->SetTextColor(0,0,0);


if (!empty($reporte['foto']) && file_exists("../uploads/".$reporte['foto'])) {
    $pdf->Ln(5);
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,'Foto adjunta:',0,1);
    $pdf->Image("../uploads/".$reporte['foto'], 10, $pdf->GetY(), 60); // ancho máx 60mm
    $pdf->Ln(65);
}


$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Fecha Ocurrencia:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,$reporte['fecha_ocurrencia'],0,1);


$pdf->SetFont('Arial','B',12);
$pdf->Cell(50,10,'Fecha Creacion:',0,0);
$pdf->SetFont('Arial','',12);
$pdf->Cell(0,10,$reporte['fecha_creacion'],0,1);



$nombreArchivo = 'reporte_incidencia_'.$reporte['id'].'.pdf';
$pdf->Output('D', $nombreArchivo);
exit;
