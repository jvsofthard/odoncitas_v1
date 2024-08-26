<?php
include('includes/db.php');

// Obtener parámetros de la solicitud
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$id_paciente = isset($_GET['id_paciente']) ? $_GET['id_paciente'] : '';

// Consultar los datos del reporte
$sql = "SELECT Pagos.id, Pacientes.nombre_completo AS paciente, Citas.id AS cita_id, Citas.fecha_hora_solicitada, Pagos.monto, Pagos.fecha_pago
        FROM Pagos
        INNER JOIN Citas ON Pagos.id_cita = Citas.id
        INNER JOIN Pacientes ON Citas.id_paciente = Pacientes.id
        WHERE 1=1";

if ($fecha_inicio) {
    $sql .= " AND Pagos.fecha_pago >= '$fecha_inicio'";
}
if ($fecha_fin) {
    $sql .= " AND Pagos.fecha_pago <= '$fecha_fin'";
}
if ($id_paciente) {
    $sql .= " AND Citas.id_paciente = '$id_paciente'";
}

$result = $conn->query($sql);

// Crear archivo CSV
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename="reporte_pagos.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Paciente', 'ID de Cita', 'Fecha y Hora de Cita', 'Monto', 'Fecha de Pago']);

while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

fclose($output);
exit();
?>
