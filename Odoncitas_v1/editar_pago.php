<?php
include('includes/db.php');
include('includes/header.php');

// Obtener el ID del pago a editar desde la URL
$id_pago = $_GET['id'];

// Consulta para obtener los detalles del pago
$sql = "SELECT * FROM Pagos WHERE id = $id_pago";
$result = $conn->query($sql);

// Verificar si se encontró el pago
if ($result->num_rows > 0) {
    $pago = $result->fetch_assoc();
} else {
    echo "Pago no encontrado.";
    exit;
}

// Variable para controlar si se muestra el mensaje de éxito
$mensaje_exito = false;

// Lógica para manejar la actualización del pago
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fecha_pago = $_POST['fecha_pago'];
    $monto = $_POST['monto'];
    $metodo_pago = $_POST['metodo_pago'];
    $notas = $_POST['notas'];

    $sql_update = "UPDATE Pagos SET monto='$monto', fecha_pago='$fecha_pago', metodo_pago='$metodo_pago', notas='$notas' WHERE id = $id_pago";

    if ($conn->query($sql_update) === TRUE) {
        $mensaje_exito = true; // Activar el mensaje de éxito
    } else {
        echo "Error: " . $sql_update . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Pago</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Estilos básicos para el modal */
        .modal {
            display: none; /* Oculto por defecto */
            position: fixed; /* Fijo en la pantalla */
            z-index: 1; /* Encima del contenido */
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto; /* Desplazamiento si es necesario */
            background-color: rgb(0,0,0); /* Color de fondo */
            background-color: rgba(0,0,0,0.4); /* Fondo con transparencia */
            padding-top: 60px;
        }
        
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; /* Centramos verticalmente */
            padding: 20px;
            border: 1px solid #888;
            width: 80%; /* Ancho del modal */
            max-width: 500px; /* Ancho máximo del modal */
        }
        
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
    <script>
        // Función para mostrar el modal
        function mostrarModal() {
            var modal = document.getElementById("modalExito");
            modal.style.display = "block";
        }

        // Función para cerrar el modal
        function cerrarModal() {
            var modal = document.getElementById("modalExito");
            modal.style.display = "none";
        }

        // Verificar si el pago se actualizó exitosamente
        <?php if ($mensaje_exito): ?>
        window.onload = function() {
            mostrarModal();
        };
        <?php endif; ?>
    </script>
</head>
<body>
    <div class="container">
        <h2>Editar Pago</h2>
        <form action="editar_pago.php?id=<?php echo $id_pago; ?>" method="post">
            <label for="fecha_pago">Fecha de Pago:</label>
            <input type="date" id="fecha_pago" name="fecha_pago" value="<?php echo $pago['fecha_pago']; ?>" required><br>

            <label for="monto">Monto:</label>
            <input type="text" id="monto" name="monto" value="<?php echo $pago['monto']; ?>" required><br>

            <label for="metodo_pago">Método de Pago:</label>
            <select id="metodo_pago" name="metodo_pago" required>
                <option value="Efectivo" <?php echo $pago['metodo_pago'] == 'Efectivo' ? 'selected' : ''; ?>>Efectivo</option>
                <option value="Tarjeta de Crédito" <?php echo $pago['metodo_pago'] == 'Tarjeta de Crédito' ? 'selected' : ''; ?>>Tarjeta de Crédito</option>
                <option value="Transferencia" <?php echo $pago['metodo_pago'] == 'Transferencia' ? 'selected' : ''; ?>>Transferencia</option>
                <option value="Otro" <?php echo $pago['metodo_pago'] == 'Otro' ? 'selected' : ''; ?>>Otro</option>
            </select><br>

            <label for="notas">Notas:</label>
            <textarea id="notas" name="notas"><?php echo $pago['notas']; ?></textarea><br>

            <input type="submit" value="Actualizar">
            <a href="listar_pagos.php" class="btn btn-secondary">Volver al listado</a>
        </form>
    </div>

    <!-- Modal de éxito -->
    <div id="modalExito" class="modal">
        <div class="modal-content">
            <span class="close" onclick="cerrarModal()">&times;</span>
            <p>Pago actualizado exitosamente.</p>
        </div>
    </div>

<?php
include('includes/footer.php');
?>
</body>
</html>
