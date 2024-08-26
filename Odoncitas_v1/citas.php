<?php
include('includes/db.php');
include('includes/header.php');

// Lógica para manejar la inserción de nuevas citas
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_paciente = $_POST['id_paciente'];
    $id_especialista = $_POST['id_especialista'];
    $fecha_hora_solicitada = $_POST['fecha_hora_solicitada'];
    $tipo_cita = $_POST['tipo_cita'];
    $aseguradora = $_POST['aseguradora'];
    $notas_adicionales = $_POST['notas_adicionales'];
    $motivo_consulta = $_POST['motivo_consulta'];

    $sql = "INSERT INTO Citas (id_paciente, id_especialista, fecha_hora_solicitada, tipo_cita, aseguradora, notas_adicionales, motivo_consulta)
            VALUES ('$id_paciente', '$id_especialista', '$fecha_hora_solicitada', '$tipo_cita', '$aseguradora', '$notas_adicionales', '$motivo_consulta')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Cita creada exitosamente.</p>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Cita</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Crear Cita</h2>
        <form action="citas.php" method="post">
            <label for="id_paciente">Paciente:</label>
            <select id="id_paciente" name="id_paciente" required>
                <?php
                $result = $conn->query("SELECT id, nombre_completo FROM Pacientes");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nombre_completo'] . "</option>";
                }
                ?>
            </select><br>

            <label for="id_especialista">Especialista:</label>
            <select id="id_especialista" name="id_especialista" required>
                <?php
                $result = $conn->query("SELECT id, nombre_completo FROM Especialistas");
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nombre_completo'] . "</option>";
                }
                ?>
            </select><br>

            <label for="fecha_hora_solicitada">Fecha y hora solicitada:</label>
            <input type="datetime-local" id="fecha_hora_solicitada" name="fecha_hora_solicitada" required><br>

            <label for="tipo_cita">Tipo de cita:</label>
            <input type="text" id="tipo_cita" name="tipo_cita" required><br>

            <label for="aseguradora">Aseguradora:</label>
            <input type="text" id="aseguradora" name="aseguradora"><br>

            <label for="notas_adicionales">Notas adicionales:</label>
            <textarea id="notas_adicionales" name="notas_adicionales"></textarea><br>

            <label for="motivo_consulta">Motivo de la consulta:</label>
            <textarea id="motivo_consulta" name="motivo_consulta" required></textarea><br>

            <input type="submit" value="Guardar">
            <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
        </form>
    </div>
<?php
include('includes/footer.php');
?>

</body>
</html>
