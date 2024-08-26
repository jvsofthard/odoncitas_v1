<?php
include('includes/db.php');

// Verificar si se recibió un ID de factura
if (isset($_GET['id'])) {
    $id_factura = $_GET['id'];
    
    // Confirmar la eliminación
    if (isset($_POST['confirmar'])) {
        // Eliminar la factura
        $sql = "DELETE FROM Facturas WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_factura);
        
        if ($stmt->execute()) {
            echo "<p>Factura eliminada exitosamente.</p>";
            echo '<a href="listar_facturas.php">Volver al listado de facturas</a>';
        } else {
            echo "Error al eliminar la factura: " . $stmt->error;
        }
        
        $stmt->close();
        $conn->close();
        exit;
    }
} else {
    echo "ID de factura no proporcionado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Eliminar Factura</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Eliminar Factura</h2>
        <form action="eliminar_factura.php?id=<?php echo htmlspecialchars($id_factura); ?>" method="post">
            <p>¿Está seguro de que desea eliminar esta factura?</p>
            <input type="submit" name="confirmar" value="Confirmar">
            <a href="listado_facturas.php">Cancelar</a>
        </form>
    </div>
</body>
</html>
