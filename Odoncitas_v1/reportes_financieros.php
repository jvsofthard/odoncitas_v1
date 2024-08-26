<?php
include('includes/db.php');
include('includes/header.php');

// Variables para los filtros
$fecha_inicio = isset($_POST['fecha_inicio']) ? $_POST['fecha_inicio'] : '';
$fecha_fin = isset($_POST['fecha_fin']) ? $_POST['fecha_fin'] : '';
$id_paciente = isset($_POST['id_paciente']) ? $_POST['id_paciente'] : '';

// Obtener pacientes para el filtro
$sql_pacientes = "SELECT id, nombre_completo FROM Pacientes";
$result_pacientes = $conn->query($sql_pacientes);

// Lógica para generar el reporte
$report_data = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sql = "SELECT Pagos.id, Pacientes.nombre_completo AS paciente, Citas.id AS cita_id, Citas.fecha_hora_solicitada, Pagos.monto, Pagos.fecha_pago
            FROM Pagos
            INNER JOIN Citas ON Pagos.id_cita = Citas.id
            INNER JOIN Pacientes ON Citas.id_paciente = Pacientes.id
            WHERE 1=1";

    if ($fecha_inicio) {
        $sql .= " AND Pagos.fecha_pago >= '$fecha_inicio'";
    }
    if ($fecha_fin) {
        $sql .= " AND Pagos.fecha_pago <= '$fecha_fin'";
    }
    if ($id_paciente) {
        $sql .= " AND Citas.id_paciente = '$id_paciente'";
    }

    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $report_data[] = $row;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes Financieros</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Reportes Financieros</h2>
        <form action="reportes_financieros.php" method="post">
            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>"><br>

            <label for="fecha_fin">Fecha de Fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>"><br>

            <label for="id_paciente">Paciente:</label>
            <select id="id_paciente" name="id_paciente">
                <option value="">Todos</option>
                <?php
                if ($result_pacientes->num_rows > 0) {
                    while ($row = $result_pacientes->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'" . ($id_paciente == $row['id'] ? " selected" : "") . ">" . $row['nombre_completo'] . "</option>";
                    }
                }
                ?>
            </select><br>

            <input type="submit" value="Generar Reporte">
            <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
        </form>

        <?php if (!empty($report_data)): ?>
            <h3>Reporte de Pagos</h3>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Paciente</th>
                        <th>ID de Cita</th>
                        <th>Fecha y Hora de Cita</th>
                        <th>Monto</th>
                        <th>Fecha de Pago</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($report_data as $row): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo $row['paciente']; ?></td>
                            <td><?php echo $row['cita_id']; ?></td>
                            <td><?php echo $row['fecha_hora_solicitada']; ?></td>
                            <td><?php echo $row['monto']; ?></td>
                            <td><?php echo $row['fecha_pago']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <a href="exportar_reporte.php?fecha_inicio=<?php echo urlencode($fecha_inicio); ?>&fecha_fin=<?php echo urlencode($fecha_fin); ?>&id_paciente=<?php echo urlencode($id_paciente); ?>" target="_blank">Exportar a CSV</a>
        <?php endif; ?>
    </div>
</body>
</html>

<?php
include('includes/footer.php');
?>
