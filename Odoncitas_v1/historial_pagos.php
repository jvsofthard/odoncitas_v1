<?php
include('includes/db.php');
include('includes/header.php');

// Consultar pagos facturados
$sql_historial = "SELECT Pagos.id, Pacientes.nombre_completo AS paciente, Citas.id AS cita_id, Citas.fecha_hora_solicitada, Pagos.monto, Pagos.fecha_pago, Pagos.metodo_pago
                  FROM Pagos
                  INNER JOIN Citas ON Pagos.id_cita = Citas.id
                  INNER JOIN Pacientes ON Citas.id_paciente = Pacientes.id
                  WHERE Pagos.estado = 'Facturado'";
$result_historial = $conn->query($sql_historial);

// Opcional: Obtener parámetros de búsqueda si se utilizan
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$id_paciente = isset($_GET['id_paciente']) ? $_GET['id_paciente'] : '';

// Consulta base para obtener pagos
$sql = "SELECT Pagos.id, Pacientes.nombre_completo AS paciente, Citas.id AS cita_id, Pagos.monto, Pagos.fecha_pago, Pagos.metodo_pago
        FROM Pagos
        INNER JOIN Citas ON Pagos.id_cita = Citas.id
        INNER JOIN Pacientes ON Citas.id_paciente = Pacientes.id
        WHERE 1=1";

// Agregar filtros a la consulta si se proporcionan
$params = [];
$types = '';
if ($fecha_inicio) {
    $sql .= " AND Pagos.fecha_pago >= ?";
    $params[] = $fecha_inicio;
    $types .= 's';
}
if ($fecha_fin) {
    $sql .= " AND Pagos.fecha_pago <= ?";
    $params[] = $fecha_fin;
    $types .= 's';
}
if ($id_paciente) {
    $sql .= " AND Citas.id_paciente = ?";
    $params[] = $id_paciente;
    $types .= 'i';
}

// Preparar y ejecutar la consulta
$stmt = $conn->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historial de Pagos</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        function printPage() {
            window.print();
        }

        function exportCSV() {
            var csv = 'ID,Paciente,Cita ID,Monto,Fecha de Pago,Método de Pago\n';
            var rows = document.querySelectorAll('table tr');

            rows.forEach(function(row) {
                var cols = row.querySelectorAll('td, th');
                var rowCsv = Array.from(cols).map(function(col) {
                    return '"' + col.innerText.replace(/"/g, '""') + '"';
                }).join(',');
                csv += rowCsv + '\n';
            });

            var csvFile = new Blob([csv], { type: 'text/csv' });
            var downloadLink = document.createElement('a');
            downloadLink.download = 'historial_pagos.csv';
            downloadLink.href = URL.createObjectURL(csvFile);
            downloadLink.click();
        }

        function exportPDF() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();

            doc.setFontSize(12);
            doc.text('Historial de Pagos', 10, 10);

            let startY = 20;
            const headers = ['ID', 'Paciente', 'Cita ID', 'Monto', 'Fecha de Pago', 'Método de Pago'];
            headers.forEach((header, index) => {
                doc.text(header, 10 + (index * 30), startY);
            });

            const rows = document.querySelectorAll('table tbody tr');
            rows.forEach((row, rowIndex) => {
                const cols = row.querySelectorAll('td');
                let x = 10;
                cols.forEach((col, colIndex) => {
                    doc.text(col.textContent, x, startY + ((rowIndex + 1) * 10));
                    x += 30;
                });
            });

            doc.save('historial_pagos.pdf');
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Historial de Pagos</h2>
        
        <!-- Formulario de búsqueda -->
        <form action="historial_pagos.php" method="get">
            <label for="fecha_inicio">Fecha Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
            
            <label for="fecha_fin">Fecha Fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>">
            
            <label for="id_paciente">Paciente:</label>
            <select id="id_paciente" name="id_paciente">
                <option value="">Todos</option>
                <?php
                $sql_pacientes = "SELECT id, nombre_completo FROM Pacientes";
                $result_pacientes = $conn->query($sql_pacientes);
                while ($row = $result_pacientes->fetch_assoc()) {
                    $selected = ($row['id'] == $id_paciente) ? 'selected' : '';
                    echo "<option value='" . $row['id'] . "' $selected>" . $row['nombre_completo'] . "</option>";
                }
                ?>
            </select><br>

            <input type="submit" value="Buscar">
        </form>

        <!-- Botones de exportación e impresión -->
        <button onclick="exportCSV()">Exportar a CSV</button>
        <button onclick="exportPDF()">Exportar a PDF</button>
        <button onclick="printPage()">Imprimir</button>

        <!-- Tabla de pagos -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Cita ID</th>
                    <th>Monto</th>
                    <th>Fecha de Pago</th>
                    <th>Método de Pago</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['paciente']); ?></td>
                    <td><?php echo htmlspecialchars($row['cita_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['monto']); ?></td>
                    <td><?php echo htmlspecialchars($row['fecha_pago']); ?></td>
                    <td><?php echo htmlspecialchars($row['metodo_pago']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
    </div>
</body>
</html>

<?php
$stmt->close();
$conn->close();
include('includes/footer.php');
?>
