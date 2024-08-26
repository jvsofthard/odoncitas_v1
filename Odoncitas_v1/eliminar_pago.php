<?php
include('includes/db.php');

// Obtener el ID del pago desde la URL
$id_pago = $_GET['id'];

// Eliminar el pago de la base de datos
$sql = "DELETE FROM Pagos WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_pago);

if ($stmt->execute()) {
    header('Location: listar_pagos.php'); // Redirigir al listado de pagos
    exit;
} else {
    echo "Error al eliminar el pago: " . $conn->error;
}
?>
