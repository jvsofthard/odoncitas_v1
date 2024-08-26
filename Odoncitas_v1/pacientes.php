<?php
include('includes/db.php');
include('includes/header.php');

// Lógica para manejar la inserción de nuevos pacientes
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_completo = $_POST['nombre_completo'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $edad = $_POST['edad'];
    $sexo = $_POST['sexo'];
    $nacionalidad = $_POST['nacionalidad'];
    $direccion = $_POST['direccion'];
    $contacto = $_POST['contacto'];
    $correo_electronico = $_POST['correo_electronico'];
    $aseguradora = $_POST['aseguradora'];
    $numero_seguridad_social = $_POST['numero_seguridad_social'];

    $sql = "INSERT INTO Pacientes (nombre_completo, fecha_nacimiento, edad, sexo, nacionalidad, direccion, contacto, correo_electronico, aseguradora, numero_seguridad_social)
            VALUES ('$nombre_completo', '$fecha_nacimiento', '$edad', '$sexo', '$nacionalidad', '$direccion', '$contacto', '$correo_electronico', '$aseguradora', '$numero_seguridad_social')";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Paciente agregado exitosamente.</p>";
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
    <title>Crear Paciente</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Crear Paciente</h2>
        <form action="pacientes.php" method="post">
            <label for="nombre_completo">Nombre completo:</label>
            <input type="text" id="nombre_completo" name="nombre_completo" required><br>

            <label for="fecha_nacimiento">Fecha de nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required><br>

            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" required><br>

            <label for="sexo">Sexo:</label>
            <select id="sexo" name="sexo" required>
                <option value="Masculino">Masculino</option>
                <option value="Femenino">Femenino</option>
                <option value="Otro">Otro</option>
            </select><br>

            <label for="nacionalidad">Nacionalidad:</label>
            <input type="text" id="nacionalidad" name="nacionalidad"><br>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion"><br>

            <label for="contacto">Contacto:</label>
            <input type="text" id="contacto" name="contacto"><br>

            <label for="correo_electronico">Correo electrónico:</label>
            <input type="email" id="correo_electronico" name="correo_electronico" required><br>

            <label for="aseguradora">Aseguradora:</label>
            <input type="text" id="aseguradora" name="aseguradora"><br>

            <label for="numero_seguridad_social">Número de seguridad social:</label>
            <input type="text" id="numero_seguridad_social" name="numero_seguridad_social"><br>

            <input type="submit" value="Guardar">
            <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
        </form>
    </div>
<?php
include('includes/footer.php');
?>

</body>
</html>
