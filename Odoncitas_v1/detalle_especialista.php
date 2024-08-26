<?php
include('includes/db.php');
include('includes/header.php');

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Especialista</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Detalles del Especialista</h2>
        <p><strong>Nombre Completo:</strong> <?php echo htmlspecialchars($row['nombre_completo']); ?></p>
        <p><strong>Especialidad:</strong> <?php echo htmlspecialchars($row['especialidad']); ?></p>
        <p><strong>Contacto:</strong> <?php echo htmlspecialchars($row['contacto']); ?></p>
        <p><strong>Correo:</strong> <?php echo htmlspecialchars($row['correo']); ?></p>
        <p><strong>Turno:</strong> <?php echo htmlspecialchars($row['turno']); ?></p>
        <a href="editar_especialista.php?id=<?php echo $row['id']; ?>" class="btn btn-primary">Editar</a>
        <a href="listar_especialistas.php" class="btn btn-secondary">Volver</a>
    </div>

<?php
include('includes/footer.php');
?>
</body>
</html>
