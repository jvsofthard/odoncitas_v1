<?php
include('includes/db.php');
include('includes/header.php');

// Verifica si se ha pasado un ID de cita a través de la URL
if (isset($_GET['id'])) {
    $id_cita = $_GET['id'];

    // Consulta SQL para obtener los detalles de la cita
    $sql = "
        SELECT *
        FROM Citas
        WHERE id = '$id_cita'
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

// Lógica para manejar la actualización de la cita
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_paciente = $_POST['id_paciente'];
    $id_especialista = $_POST['id_especialista'];
    $fecha_hora_solicitada = $_POST['fecha_hora_solicitada'];
    $tipo_cita = $_POST['tipo_cita'];
    $aseguradora = $_POST['aseguradora'];
    $notas_adicionales = $_POST['notas_adicionales'];
    $motivo_consulta = $_POST['motivo_consulta'];

    $sql_update = "
        UPDATE Citas
        SET id_paciente = '$id_paciente', 
            id_especialista = '$id_especialista', 
            fecha_hora_solicitada = '$fecha_hora_solicitada', 
            tipo_cita = '$tipo_cita', 
            aseguradora = '$aseguradora', 
            notas_adicionales = '$notas_adicionales', 
            motivo_consulta = '$motivo_consulta'
        WHERE id = '$id_cita'
    ";

    if ($conn->query($sql_update) === TRUE) {
        echo "<p>Cita actualizada exitosamente.</p>";
        header("Location: detalle_cita.php?id=$id_cita");
        exit();
    } else {
        echo "Error al actualizar la cita: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Cita</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Editar Cita</h2>
        <form action="editar_cita.php?id=<?php echo $id_cita; ?>" method="post">
            <label for="id_paciente">Paciente:</label>
            <select id="id_paciente" name="id_paciente" required>
                <?php
                $result_pacientes = $conn->query("SELECT id, nombre_completo FROM Pacientes");
                while ($paciente = $result_pacientes->fetch_assoc()) {
                    echo "<option value='" . $paciente['id'] . "' " . ($paciente['id'] == $row['id_paciente'] ? 'selected' : '') . ">" . $paciente['nombre_completo'] . "</option>";
                }
                ?>
            </select><br>

            <label for="id_especialista">Especialista:</label>
            <select id="id_especialista" name="id_especialista" required>
                <?php
                $result_especialistas = $conn->query("SELECT id, nombre_completo FROM Especialistas");
                while ($especialista = $result_especialistas->fetch_assoc()) {
                    echo "<option value='" . $especialista['id'] . "' " . ($especialista['id'] == $row['id_especialista'] ? 'selected' : '') . ">" . $especialista['nombre_completo'] . "</option>";
                }
                ?>
            </select><br>

            <label for="fecha_hora_solicitada">Fecha y hora solicitada:</label>
            <input type="datetime-local" id="fecha_hora_solicitada" name="fecha_hora_solicitada" value="<?php echo date('Y-m-d\TH:i', strtotime($row['fecha_hora_solicitada'])); ?>" required><br>

            <label for="tipo_cita">Tipo de cita:</label>
            <input type="text" id="tipo_cita" name="tipo_cita" value="<?php echo htmlspecialchars($row['tipo_cita']); ?>" required><br>

            <label for="aseguradora">Aseguradora:</label>
            <input type="text" id="aseguradora" name="aseguradora" value="<?php echo htmlspecialchars($row['aseguradora']); ?>"><br>

            <label for="notas_adicionales">Notas adicionales:</label>
            <textarea id="notas_adicionales" name="notas_adicionales"><?php echo htmlspecialchars($row['notas_adicionales']); ?></textarea><br>

            <label for="motivo_consulta">Motivo de la consulta:</label>
            <textarea id="motivo_consulta" name="motivo_consulta" required><?php echo htmlspecialchars($row['motivo_consulta']); ?></textarea><br>

            <input type="submit" value="Actualizar">
            <a href="listar_citas.php" class="btn btn-secondary">Volver al Listado</a>
        </form>
        <a href="detalle_cita.php?id=<?php echo $id_cita; ?>" class="btn btn-secondary">Cancelar</a>
    </div>
<?php
include('includes/footer.php');
?>

</body>
</html>
