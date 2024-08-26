<?php
include('includes/db.php');

// Obtener datos del formulario
$id_cita = $_POST['id_cita'];
$id_paciente = $_POST['id_paciente'];
$monto = $_POST['monto'];
$fecha_pago = $_POST['fecha_pago'];
$metodo_pago = $_POST['metodo_pago'];
$notas = $_POST['notas'];

// Realizar la inserción del pago en la base de datos
$sql = "INSERT INTO Pagos (id_cita, id_paciente, monto, fecha_pago, metodo_pago, notas) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iissss", $id_cita, $id_paciente, $monto, $fecha_pago, $metodo_pago, $notas);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "error";
}
?>
