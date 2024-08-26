<?php
include('includes/db.php');
include('includes/header.php');

// Variables para filtros
$fecha_desde = isset($_GET['fecha_desde']) ? $_GET['fecha_desde'] : '';
$fecha_hasta = isset($_GET['fecha_hasta']) ? $_GET['fecha_hasta'] : '';
$paciente = isset($_GET['paciente']) ? $_GET['paciente'] : '';
$metodo_pago = isset($_GET['metodo_pago']) ? $_GET['metodo_pago'] : '';

// Construir la consulta SQL con filtros
$sql_facturas = "SELECT Facturas.id, Facturas.fecha_factura, Facturas.monto, Facturas.metodo_pago, 
                 Pacientes.nombre_completo AS paciente, Especialistas.nombre_completo AS especialista
                 FROM Facturas
                 INNER JOIN Pacientes ON Facturas.paciente_id = Pacientes.id
                 INNER JOIN Citas ON Facturas.cita_id = Citas.id
                 INNER JOIN Especialistas ON Citas.id_especialista = Especialistas.id
                 WHERE 1=1";

if ($fecha_desde && $fecha_hasta) {
    $sql_facturas .= " AND Facturas.fecha_factura BETWEEN '$fecha_desde' AND '$fecha_hasta'";
}

if ($paciente) {
    $sql_facturas .= " AND Pacientes.nombre_completo LIKE '%$paciente%'";
}

if ($metodo_pago) {
    $sql_facturas .= " AND Facturas.metodo_pago = '$metodo_pago'";
}

$result_facturas = $conn->query($sql_facturas);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Facturas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Historial de Facturas</h2>
        
        <!-- Formulario de Filtros -->
        <form method="GET" action="historial_facturas.php">
            <label for="fecha_desde">Fecha Desde:</label>
            <input type="date" id="fecha_desde" name="fecha_desde" value="<?php echo $fecha_desde; ?>">

            <label for="fecha_hasta">Fecha Hasta:</label>
            <input type="date" id="fecha_hasta" name="fecha_hasta" value="<?php echo $fecha_hasta; ?>">

            <label for="paciente">Paciente:</label>
            <input type="text" id="paciente" name="paciente" value="<?php echo $paciente; ?>">

            <label for="metodo_pago">Método de Pago:</label>
            <select id="metodo_pago" name="metodo_pago">
                <option value="">Todos</option>
                <option value="Efectivo" <?php if ($metodo_pago == "Efectivo") echo "selected"; ?>>Efectivo</option>
                <option value="Tarjeta de Crédito" <?php if ($metodo_pago == "Tarjeta de Crédito") echo "selected"; ?>>Tarjeta de Crédito</option>
                <option value="Transferencia" <?php if ($metodo_pago == "Transferencia") echo "selected"; ?>>Transferencia</option>
                <option value="Otro" <?php if ($metodo_pago == "Otro") echo "selected"; ?>>Otro</option>
            </select>

            <button type="submit">Filtrar</button>
        </form>

        <!-- Tabla de Facturas -->
        <table border="1">
            <thead>
                <tr>
                    <th>ID Factura</th>
                    <th>Fecha</th>
                    <th>Paciente</th>
                    <th>Especialista</th>
                    <th>Monto</th>
                    <th>Método de Pago</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_facturas->num_rows > 0) {
                    while ($row = $result_facturas->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['fecha_factura'] . "</td>";
                        echo "<td>" . $row['paciente'] . "</td>";
                        echo "<td>" . $row['especialista'] . "</td>";
                        echo "<td>" . $row['monto'] . "</td>";
                        echo "<td>" . $row['metodo_pago'] . "</td>";
                        echo "<td>";
                        echo "<a href='ver_factura.php?id=" . $row['id'] . "'>Ver Detalles</a> | ";
                        echo "<a href='exportar_factura.php?id=" . $row['id'] . "'>Exportar a PDF</a> | ";
                        echo "<a href='eliminar_factura.php?id=" . $row['id'] . "' onclick='return confirm(\"¿Estás seguro de que deseas eliminar esta factura?\")'>Eliminar</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay facturas disponibles.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
    </div>
</body>
</html>

<?php
include('includes/footer.php');
?>
