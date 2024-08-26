<?php
include('includes/db.php');
include('includes/header.php');

// Obtener pagos de la base de datos
$sql_pagos = "SELECT id, monto, fecha_pago, metodo_pago, id_paciente, id_cita FROM Pagos";
$result_pagos = $conn->query($sql_pagos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generar Factura</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Generar Factura</h2>
        <form action="procesar_factura.php" method="post">
            <label for="id_pago">Pago:</label>
            <select id="id_pago" name="id_pago" required>
                <?php
                if ($result_pagos->num_rows > 0) {
                    while ($row = $result_pagos->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . "Pago #" . $row['id'] . " - " . $row['monto'] . " - " . $row['fecha_pago'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay pagos disponibles</option>";
                }
                ?>
            </select><br>

            <label for="fecha_factura">Fecha de Factura:</label>
            <input type="date" id="fecha_factura" name="fecha_factura" required><br>

            <input type="submit" value="Generar Factura">
            <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
        </form>
       

    </div>
</body>
</html>

<?php
include('includes/footer.php');
?>
