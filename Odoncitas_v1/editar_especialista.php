<?php
include('includes/db.php');

// Verifica si se ha pasado un ID de especialista a través de la URL
if (isset($_GET['id'])) {
    $id_especialista = $_GET['id'];

    // Consulta para obtener los detalles del especialista
    $sql = "SELECT * FROM Especialistas WHERE id = '$id_especialista'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "<p>Especialista no encontrado.</p>";
        exit();
    }
} else {
    echo "<p>ID de especialista no proporcionado.</p>";
    exit();
}

// Lógica para manejar la actualización de los datos del especialista
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre_completo = $_POST['nombre_completo'];
    $especialidad = $_POST['especialidad'];
    $contacto = $_POST['contacto'];
    $correo = $_POST['correo'];
    $turno = $_POST['turno'];

    $sql = "UPDATE Especialistas SET 
            nombre_completo = '$nombre_completo', 
            especialidad = '$especialidad', 
            contacto = '$contacto', 
            correo = '$correo', 
            turno = '$turno'
            WHERE id = '$id_especialista'";

    if ($conn->query($sql) === TRUE) {
        echo "<p>Especialista actualizado exitosamente.</p>";
        header("Location: listar_especialistas.php");
        exit();
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
    <title>Editar Especialista</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Editar Especialista</h2>
        <form action="editar_especialista.php?id=<?php echo $id_especialista; ?>" method="post">
            <label for="nombre_completo">Nombre completo:</label>
            <input type="text" id="nombre_completo" name="nombre_completo" value="<?php echo htmlspecialchars($row['nombre_completo']); ?>" required><br>

            <label for="especialidad">Especialidad:</label>
            <input type="text" id="especialidad" name="especialidad" value="<?php echo htmlspecialchars($row['especialidad']); ?>" required><br>

            <label for="contacto">Contacto:</label>
            <input type="text" id="contacto" name="contacto" value="<?php echo htmlspecialchars($row['contacto']); ?>"><br>

            <label for="correo">Correo electrónico:</label>
            <input type="email" id="correo" name="correo" value="<?php echo htmlspecialchars($row['correo']); ?>"><br>

            <label for="turno">Turno:</label>
            <select id="turno" name="turno" required>
                <option value="Mañana" <?php echo ($row['turno'] == 'Mañana') ? 'selected' : ''; ?>>Mañana</option>
                <option value="Tarde" <?php echo ($row['turno'] == 'Tarde') ? 'selected' : ''; ?>>Tarde</option>
                <option value="Noche" <?php echo ($row['turno'] == 'Noche') ? 'selected' : ''; ?>>Noche</option>
            </select><br>

            <input type="submit" value="Actualizar">

             <a href="listar_especialistas.php" class="btn btn-secondary">Cancelar</a>
        </form>
       
    </div>

<?php
include('includes/footer.php');
?>
</body>
</html>
