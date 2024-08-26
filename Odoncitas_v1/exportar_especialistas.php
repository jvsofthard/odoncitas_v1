<?php
include('includes/db.php');

// Configura el nombre del archivo CSV
$filename = "especialistas_" . date("Y-m-d") . ".csv";

// Establece el encabezado para la descarga del archivo
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=' . $filename);

// Abre el archivo para escritura
$output = fopen('php://output', 'w');

// Escribe el encabezado de las columnas en el archivo CSV
fputcsv($output, array('ID', 'Nombre Completo', 'Especialidad', 'Contacto', 'Correo Electrónico', 'Turno'));

// Consulta para obtener los datos de los especialistas
$sql = "SELECT * FROM Especialistas";
$result = $conn->query($sql);

// Escribe los datos de los especialistas en el archivo CSV
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

// Cierra el archivo
fclose($output);
exit();
?>
