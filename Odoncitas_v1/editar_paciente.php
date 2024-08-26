<?php
include('includes/db.php');
include('includes/header.php');

$id = $_GET['id']; // Obtener el ID del paciente desde la URL

// Consultar los detalles del paciente
$sql = "SELECT * FROM Pacientes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$paciente = $result->fetch_assoc();

if (!$paciente) {
    echo "<p>Paciente no encontrado.</p>";
    exit;
}

// Procesar el formulario de edición
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

    $sql = "UPDATE Pacientes SET 
                nombre_completo = ?, 
                fecha_nacimiento = ?, 
                edad = ?, 
                sexo = ?, 
                nacionalidad = ?, 
                direccion = ?, 
                contacto = ?, 
                correo_electronico = ?, 
                aseguradora = ?, 
                numero_seguridad_social = ?
            WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssisssssssi", $nombre_completo, $fecha_nacimiento, $edad, $sexo, $nacionalidad, $direccion, $contacto, $correo_electronico, $aseguradora, $numero_seguridad_social, $id);

    if ($stmt->execute()) {
        echo "<p>Paciente actualizado exitosamente.</p>";
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Paciente</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Editar Paciente</h2>
        <form action="editar_paciente.php?id=<?php echo htmlspecialchars($id); ?>" method="post">
            <label for="nombre_completo">Nombre completo:</label>
            <input type="text" id="nombre_completo" name="nombre_completo" value="<?php echo htmlspecialchars($paciente['nombre_completo']); ?>" required><br>

            <label for="fecha_nacimiento">Fecha de nacimiento:</label>
            <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($paciente['fecha_nacimiento']); ?>" required><br>

            <label for="edad">Edad:</label>
            <input type="number" id="edad" name="edad" value="<?php echo htmlspecialchars($paciente['edad']); ?>" required><br>

            <label for="sexo">Sexo:</label>
            <select id="sexo" name="sexo" required>
                <option value="Masculino" <?php echo ($paciente['sexo'] == 'Masculino') ? 'selected' : ''; ?>>Masculino</option>
                <option value="Femenino" <?php echo ($paciente['sexo'] == 'Femenino') ? 'selected' : ''; ?>>Femenino</option>
                <option value="Otro" <?php echo ($paciente['sexo'] == 'Otro') ? 'selected' : ''; ?>>Otro</option>
            </select><br>

            <label for="nacionalidad">Nacionalidad:</label>
            <input type="text" id="nacionalidad" name="nacionalidad" value="<?php echo htmlspecialchars($paciente['nacionalidad']); ?>"><br>

            <label for="direccion">Dirección:</label>
            <input type="text" id="direccion" name="direccion" value="<?php echo htmlspecialchars($paciente['direccion']); ?>"><br>

            <label for="contacto">Contacto:</label>
            <input type="text" id="contacto" name="contacto" value="<?php echo htmlspecialchars($paciente['contacto']); ?>"><br>

            <label for="correo_electronico">Correo electrónico:</label>
            <input type="email" id="correo_electronico" name="correo_electronico" value="<?php echo htmlspecialchars($paciente['correo_electronico']); ?>" required><br>

            <label for="aseguradora">Aseguradora:</label>
            <input type="text" id="aseguradora" name="aseguradora" value="<?php echo htmlspecialchars($paciente['aseguradora']); ?>"><br>

            <label for="numero_seguridad_social">Número de seguridad social:</label>
            <input type="text" id="numero_seguridad_social" name="numero_seguridad_social" value="<?php echo htmlspecialchars($paciente['numero_seguridad_social']); ?>"><br>

            <input type="submit" value="Actualizar"> <a href="listar_pacientes.php" class="btn btn-secondary">Volver al Listado</a>
        </form>
        
    </div>

<?php
include('includes/footer.php');
?>
</body>
</html>
