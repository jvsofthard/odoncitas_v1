<?php
include('includes/db.php');
include('includes/header.php');

// Obtener los datos del formulario
$id_especialista = $_POST['id_especialista'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];

// Consulta para obtener ingresos por especialista en el período especificado
$sql = "SELECT 
            e.nombre_completo AS especialista, 
            SUM(p.monto) AS total_ingresos 
        FROM 
            Pagos p 
        INNER JOIN 
            Citas c ON p.id_cita = c.id 
        INNER JOIN 
            Especialistas e ON c.id_especialista = e.id 
        WHERE 
            c.id_especialista = ? 
            AND p.fecha_pago BETWEEN ? AND ? 
        GROUP BY 
            e.nombre_completo";

$stmt = $conn->prepare($sql);
$stmt->bind_param('iss', $id_especialista, $fecha_inicio, $fecha_fin);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ingresos por Especialista</title>
    <link rel="stylesheet" href="css/styles.css">
    <script>
        function printReport() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Reporte de Ingresos por Especialista</h2>
        <form action="exportar_reporte_csv.php" method="post">
            <input type="hidden" name="id_especialista" value="<?php echo htmlspecialchars($id_especialista); ?>">
            <input type="hidden" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
            <input type="hidden" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>">
            <input type="submit" value="Exportar a CSV">            
        </form>
        <br><button onclick="printReport()">Imprimir Reporte</button>
        <br>
        
        <table>
            <thead>
                <tr>
                    <th>Especialista</th>
                    <th>Total Ingresos</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['especialista']) . "</td>";
                        echo "<td>" . number_format($row['total_ingresos'], 2) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='2'>No se encontraron ingresos para el especialista en el período especificado.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="reporte_ingresos_especialista.php" class="btn btn-secondary">Volver al Atras</a>
    </div>
<?php
include('includes/footer.php');
?>
</body>
</html>
