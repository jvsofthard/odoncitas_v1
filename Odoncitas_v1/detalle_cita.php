<?php
include('includes/db.php');
include('includes/header.php');

// Verifica si se ha pasado un ID de cita a través de la URL
if (isset($_GET['id'])) {
    $id_cita = $_GET['id'];

    // Consulta SQL para obtener los detalles de la cita, paciente y especialista
    $sql = "
        SELECT Citas.*, Pacientes.nombre_completo AS paciente_nombre, Pacientes.fecha_nacimiento AS paciente_fecha_nacimiento, 
               Pacientes.contacto AS paciente_contacto, Pacientes.correo_electronico AS paciente_correo, 
               Especialistas.nombre_completo AS especialista_nombre, Especialistas.especialidad AS especialista_especialidad
        FROM Citas
        JOIN Pacientes ON Citas.id_paciente = Pacientes.id
        JOIN Especialistas ON Citas.id_especialista = Especialistas.id
        WHERE Citas.id = '$id_cita'
    ";
    
    $result = $conn->query($sql);

    // Verifica si se encontró la cita
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p>Cita no encontrada.</p>";
        exit();
    }
} else {
    echo "<p>ID de cita no proporcionado.</p>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles de la Cita</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Detalles de la Cita</h2>
        <!-- Enlace para exportar a CSV -->
        <a href="exportar_detalle_cita.php?id=<?php echo $id_cita; ?>" class="btn-exportar">Exportar a CSV</a>

        <h3>Información de la Cita</h3>
        <p><strong>Fecha y Hora Solicitada:</strong> <?php echo htmlspecialchars($row['fecha_hora_solicitada']); ?></p>
        <p><strong>Tipo de Cita:</strong> <?php echo htmlspecialchars($row['tipo_cita']); ?></p>
        <p><strong>Aseguradora:</strong> <?php echo htmlspecialchars($row['aseguradora']); ?></p>
        <p><strong>Motivo de la Consulta:</strong> <?php echo htmlspecialchars($row['motivo_consulta']); ?></p>
        <p><strong>Notas Adicionales:</strong> <?php echo htmlspecialchars($row['notas_adicionales']); ?></p>

        <h3>Información del Paciente</h3>
        <p><strong>Nombre Completo:</strong> <?php echo htmlspecialchars($row['paciente_nombre']); ?></p>
        <p><strong>Fecha de Nacimiento:</strong> <?php echo htmlspecialchars($row['paciente_fecha_nacimiento']); ?></p>
        <p><strong>Contacto:</strong> <?php echo htmlspecialchars($row['paciente_contacto']); ?></p>
        <p><strong>Correo Electrónico:</strong> <?php echo htmlspecialchars($row['paciente_correo']); ?></p>

        <h3>Información del Especialista</h3>
        <p><strong>Nombre Completo:</strong> <?php echo htmlspecialchars($row['especialista_nombre']); ?></p>
        <p><strong>Especialidad:</strong> <?php echo htmlspecialchars($row['especialista_especialidad']); ?></p>

        <a href="editar_cita.php?id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-primary">Editar Cita</a>
        <a href="eliminar_cita.php?id=<?php echo htmlspecialchars($row['id']); ?>" onclick="return confirm('¿Estás seguro de que deseas eliminar esta cita?');" class="btn btn-danger">Eliminar Cita</a>
        <a href="listar_citas.php" class="btn btn-secondary">Volver al Listado</a>
    </div>
    
<?php
include('includes/footer.php');
?>

</body>
</html>
