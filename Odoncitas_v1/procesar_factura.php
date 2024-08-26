<?php
include('includes/db.php');

// Inicializar variables
$mensaje = "";
$tipo_mensaje = "";

// Verificar que se ha enviado el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pago = $_POST['id_pago'];
    $fecha_factura = $_POST['fecha_factura'];

    // Obtener detalles del pago
    $sql_pago = "SELECT monto, metodo_pago, id_paciente, id_cita FROM Pagos WHERE id = ?";
    $stmt = $conn->prepare($sql_pago);
    $stmt->bind_param("i", $id_pago);
    $stmt->execute();
    $result = $stmt->get_result();
    $pago = $result->fetch_assoc();

    // Insertar la factura en la base de datos
    $sql_factura = "INSERT INTO Facturas (id_pago, fecha_factura, monto, metodo_pago, paciente_id, cita_id)
                    VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_factura);
    $stmt->bind_param("isdsii", $id_pago, $fecha_factura, $pago['monto'], $pago['metodo_pago'], $pago['id_paciente'], $pago['id_cita']);
    
    if ($stmt->execute()) {
        // Actualizar el estado del pago a 'Facturado'
        $sql_update_pago = "UPDATE Pagos SET estado = 'Facturado' WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update_pago);
        $stmt_update->bind_param("i", $id_pago);
        $stmt_update->execute();
        
        $id_factura = $stmt->insert_id; // Obtener el ID de la factura insertada
        echo "<p>Factura generada exitosamente. <a href='exportar_factura.php?id=$id_factura' target='_blank'>Descargar Factura en PDF</a></p>";
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
    <title>Procesar Factura</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
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
        .success {
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p class="<?php echo $tipo_mensaje; ?>"><?php echo $mensaje; ?></p>
        </div>
    </div>
    
    <script>
        // Modal script
        var modal = document.getElementById("myModal");
        var span = document.getElementsByClassName("close")[0];

        window.onload = function() {
            <?php if ($tipo_mensaje): ?>
                modal.style.display = "block";
            <?php endif; ?>
        }

        span.onclick = function() {
            modal.style.display = "none";
            // Redirigir a la página de creación de facturas
            window.location.href = "crear_factura.php";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
                // Redirigir a la página de creación de facturas
                window.location.href = "crear_factura.php";
            }
        }
    </script>
</body>
</html>
