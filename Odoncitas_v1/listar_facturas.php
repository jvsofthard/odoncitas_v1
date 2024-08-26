<?php
include('includes/db.php');
include('includes/header.php');

// Inicializar variables de filtro
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$id_paciente = isset($_GET['id_paciente']) ? $_GET['id_paciente'] : '';
$metodo_pago = isset($_GET['metodo_pago']) ? $_GET['metodo_pago'] : '';

// Obtener pacientes para el filtro
$sql_pacientes = "SELECT id, nombre_completo FROM Pacientes";
$result_pacientes = $conn->query($sql_pacientes);

// Construir la consulta SQL con filtros
$sql = "SELECT Facturas.id, Facturas.fecha_factura, Facturas.monto, Facturas.metodo_pago, Pacientes.nombre_completo AS paciente, Citas.id AS cita_id
        FROM Facturas
        INNER JOIN Pagos ON Facturas.id_pago = Pagos.id
        INNER JOIN Pacientes ON Pagos.id_paciente = Pacientes.id
        INNER JOIN Citas ON Pagos.id_cita = Citas.id
        WHERE 1=1"; // Siempre verdadero para facilitar la adición de filtros

// Aplicar filtros si están establecidos
if (!empty($fecha_inicio)) {
    $sql .= " AND Facturas.fecha_factura >= '$fecha_inicio'";
}
if (!empty($fecha_fin)) {
    $sql .= " AND Facturas.fecha_factura <= '$fecha_fin'";
}
if (!empty($id_paciente)) {
    $sql .= " AND Pagos.id_paciente = '$id_paciente'";
}
if (!empty($metodo_pago)) {
    $sql .= " AND Facturas.metodo_pago = '$metodo_pago'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Facturas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Listado de Facturas</h2>
        
        <!-- Formulario de filtros -->
        <form method="get" action="listar_facturas.php">
            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>"><br>

            <label for="fecha_fin">Fecha de Fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>"><br>

            <label for="id_paciente">Paciente:</label>
            <select id="id_paciente" name="id_paciente">
                <option value="">Todos</option>
                <?php
                while ($row = $result_pacientes->fetch_assoc()) {
                    $selected = ($id_paciente == $row['id']) ? 'selected' : '';
                    echo "<option value='" . $row['id'] . "' $selected>" . $row['nombre_completo'] . "</option>";
                }
                ?>
            </select><br>

            <label for="metodo_pago">Método de Pago:</label>
            <select id="metodo_pago" name="metodo_pago">
                <option value="">Todos</option>
                <option value="Efectivo" <?php echo ($metodo_pago == 'Efectivo') ? 'selected' : ''; ?>>Efectivo</option>
                <option value="Tarjeta de Crédito" <?php echo ($metodo_pago == 'Tarjeta de Crédito') ? 'selected' : ''; ?>>Tarjeta de Crédito</option>
                <option value="Transferencia" <?php echo ($metodo_pago == 'Transferencia') ? 'selected' : ''; ?>>Transferencia</option>
                <option value="Otro" <?php echo ($metodo_pago == 'Otro') ? 'selected' : ''; ?>>Otro</option>
            </select><br>

            <input type="submit" value="Filtrar">
        </form>

        <!-- Tabla de facturas -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Fecha de Factura</th>
                    <th>Monto</th>
                    <th>Método de Pago</th>
                    <th>Paciente</th>
                    <th>Cita</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['fecha_factura']); ?></td>
                        <td><?php echo number_format($row['monto'], 2); ?></td>
                        <td><?php echo htmlspecialchars($row['metodo_pago']); ?></td>
                        <td><?php echo htmlspecialchars($row['paciente']); ?></td>
                        <td><?php echo htmlspecialchars($row['cita_id']); ?></td>
                        <td>
                            <a href="ver_factura.php?id=<?php echo htmlspecialchars($row['id']); ?>">Ver</a>

                            <a href="exportar_factura.php?id=<?php echo htmlspecialchars($row['id']); ?>" target="_blank">Descargar PDF</a>

                            <a href="eliminar_factura.php?id=<?php echo htmlspecialchars($row['id']); ?>" onclick="return confirm('¿Está seguro de que desea eliminar esta factura?');">Eliminar</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
    </div>
</body>
</html>

<?php
include('includes/footer.php');
?>
