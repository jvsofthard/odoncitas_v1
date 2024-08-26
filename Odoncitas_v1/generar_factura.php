<?php
require('fpdf/fpdf.php'); // Asegúrate de tener la biblioteca FPDF

// Obtener el ID del pago y otros detalles necesarios
$id_pago = $_POST['id_pago'];
$fecha_factura = $_POST['fecha_factura'];

// Consulta para obtener los detalles del pago
$sql_pago = "SELECT * FROM Pagos WHERE id = $id_pago";
$result_pago = $conn->query($sql_pago);
$pago = $result_pago->fetch_assoc();

// Generar el PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Factura', 0, 1, 'C');

// Agregar detalles de la factura
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, 'ID Pago: ' . $pago['id'], 0, 1);
$pdf->Cell(0, 10, 'Paciente: ' . $pago['id_paciente'], 0, 1);
$pdf->Cell(0, 10, 'Monto: ' . $pago['monto'], 0, 1);
$pdf->Cell(0, 10, 'Fecha de Pago: ' . $pago['fecha_pago'], 0, 1);
$pdf->Cell(0, 10, 'Método de Pago: ' . $pago['metodo_pago'], 0, 1);
$pdf->Cell(0, 10, 'Fecha de Factura: ' . $fecha_factura, 0, 1);

// Guardar el archivo PDF
$factura_path = 'facturas/factura_' . $id_pago . '.pdf';
$pdf->Output('F', $factura_path);

// Enviar la ruta del archivo PDF para que sea utilizado en el modal
echo json_encode(array('file' => $factura_path));
?>
