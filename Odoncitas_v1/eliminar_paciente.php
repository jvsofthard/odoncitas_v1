<?php
include('includes/db.php');
include('includes/header.php');

// Obtener el ID del paciente desde la URL
$id = $_GET['id'];

// Eliminar el paciente de la base de datos
$sql = "DELETE FROM Pacientes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    header("Location: listar_pacientes.php"); // Redirigir al listado después de eliminar
    exit;
} else {
    echo "Error: " . $stmt->error;
}
?>
