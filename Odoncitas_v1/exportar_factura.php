<?php
require('fpdf/fpdf.php');
include('includes/db.php');

// Obtener la factura
$id_factura = $_GET['id'];
$sql_factura = "SELECT * FROM Facturas WHERE id = ?";
$stmt = $conn->prepare($sql_factura);
$stmt->bind_param("i", $id_factura);
$stmt->execute();
$result = $stmt->get_result();
$factura = $result->fetch_assoc();

// Obtener el nombre del paciente
$paciente_id = $factura['paciente_id'];
$sql_paciente = "SELECT nombre_completo FROM Pacientes WHERE id = ?";
$stmt = $conn->prepare($sql_paciente);
$stmt->bind_param("i", $paciente_id);
$stmt->execute();
$result = $stmt->get_result();
$paciente = $result->fetch_assoc();

// Obtener el nombre del especialista
$cita_id = $factura['cita_id'];
$sql_cita = "SELECT id_especialista FROM Citas WHERE id = ?";
$stmt = $conn->prepare($sql_cita);
$stmt->bind_param("i", $cita_id);
$stmt->execute();
$result = $stmt->get_result();
$cita = $result->fetch_assoc();

$especialista_id = $cita['id_especialista'];
$sql_especialista = "SELECT nombre_completo FROM Especialistas WHERE id = ?";
$stmt = $conn->prepare($sql_especialista);
$stmt->bind_param("i", $especialista_id);
$stmt->execute();
$result = $stmt->get_result();
$especialista = $result->fetch_assoc();

// Crear PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Factura', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'ID: ' . $factura['id'], 0, 1);
$pdf->Cell(0, 10, 'Fecha: ' . $factura['fecha_factura'], 0, 1);
$pdf->Cell(0, 10, 'Monto: ' . $factura['monto'], 0, 1);
$pdf->Cell(0, 10, 'Método de Pago: ' . $factura['metodo_pago'], 0, 1);
$pdf->Cell(0, 10, 'Paciente: ' . $paciente['nombre_completo'], 0, 1);
$pdf->Cell(0, 10, 'Especialista: ' . $especialista['nombre_completo'], 0, 1);
$pdf->Cell(0, 10, 'Cita ID: ' . $factura['cita_id'], 0, 1);

// Descargar el archivo
$pdf->Output('D', 'factura_' . $id_factura . '.pdf');
?>
