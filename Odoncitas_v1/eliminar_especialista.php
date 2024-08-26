<?php
include('includes/db.php');

// Verifica si se ha pasado un ID de especialista a través de la URL
if (isset($_GET['id'])) {
    $id_especialista = $_GET['id'];

    // Consulta para obtener los detalles del especialista
    $sql_select = "SELECT * FROM Especialistas WHERE id = '$id_especialista'";
    $result = $conn->query($sql_select);

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

// Verifica si se ha confirmado la eliminación
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['confirmar'])) {
        // Consulta para eliminar el especialista
        $sql_delete = "DELETE FROM Especialistas WHERE id = '$id_especialista'";

        if ($conn->query($sql_delete) === TRUE) {
            echo "<p>Especialista eliminado exitosamente.</p>";
            header("Location: listar_especialistas.php");
            exit();
        } else {
            echo "Error al eliminar el especialista: " . $conn->error;
        }
    } elseif (isset($_POST['cancelar'])) {
        header("Location: listar_especialistas.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Especialista</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Eliminar Especialista</h2>
        <p>¿Estás seguro de que deseas eliminar al especialista <?php echo htmlspecialchars($row['nombre_completo']); ?>?</p>
        <form action="eliminar_especialista.php?id=<?php echo $id_especialista; ?>" method="post">
            <input type="submit" name="confirmar" value="Confirmar">
            <input type="submit" name="cancelar" value="Cancelar">
        </form>
    </div>

<?php
include('includes/footer.php');
?>
</body>
</html>
