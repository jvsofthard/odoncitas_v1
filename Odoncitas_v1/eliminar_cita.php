<?php
include('includes/db.php');

// Verifica si se ha pasado un ID de cita a través de la URL
if (isset($_GET['id'])) {
    $id_cita = $_GET['id'];

    // Consulta SQL para eliminar la cita
    $sql = "DELETE FROM Citas WHERE id = '$id_cita'";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Cita eliminada exitosamente.</p>";
    } else {
        echo "Error al eliminar la cita: " . $conn->error;
    }
} else {
    echo "<p>ID de cita no proporcionado.</p>";
    exit();
}

// Redireccionar al listado de citas después de la eliminación
header("Location: listar_citas.php");
exit();
?>
