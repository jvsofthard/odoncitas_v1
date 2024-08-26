<?php
include('includes/db.php');

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

// Definir el nombre del archivo CSV
$filename = "reporte_ingresos_especialista_" . date("Ymd") . ".csv";

// Configurar encabezados para la descarga del archivo
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Crear el archivo CSV
$output = fopen('php://output', 'w');
fputcsv($output, ['Especialista', 'Total Ingresos']);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, [$row['especialista'], number_format($row['total_ingresos'], 2)]);
    }
}

fclose($output);
exit();
?>
