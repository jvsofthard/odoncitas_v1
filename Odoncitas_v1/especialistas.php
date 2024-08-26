<?php
include('includes/db.php');
include('includes/header.php');

// Lógica para manejar la inserción de nuevos especialistas
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_completo = $_POST['nombre_completo'];
    $especialidad = $_POST['especialidad'];
    $contacto = $_POST['contacto'];
    $correo = $_POST['correo'];
    $turno = $_POST['turno'];

    $sql = "INSERT INTO Especialistas (nombre_completo, especialidad, contacto, correo, turno)
            VALUES ('$nombre_completo', '$especialidad', '$contacto', '$correo', '$turno')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Especialista agregado exitosamente.</p>";
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
    <title>Crear Especialista</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Crear Especialista</h2>
        <form action="especialistas.php" method="post">
            <label for="nombre_completo">Nombre completo:</label>
            <input type="text" id="nombre_completo" name="nombre_completo" required><br>

            <label for="especialidad">Especialidad:</label>
            <input type="text" id="especialidad" name="especialidad" required><br>

            <label for="contacto">Contacto:</label>
            <input type="text" id="contacto" name="contacto"><br>

            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo"><br>

            <label for="turno">Turno:</label>
            <select id="turno" name="turno" required>
                <option value="Mañana">Mañana</option>
                <option value="Tarde">Tarde</option>
                <option value="Noche">Noche</option>
            </select><br>

            <input type="submit" value="Guardar">

            <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
        </form>
    </div>

<?php
include('includes/footer.php');
?>
</body>
</html>
