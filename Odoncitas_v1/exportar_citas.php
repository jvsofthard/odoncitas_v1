<?php
include('includes/db.php');

// Configura el nombre del archivo CSV
$filename = "citas_" . date("Y-m-d") . ".csv";

// Establece el encabezado para la descarga del archivo
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=' . $filename);

// Abre el archivo para escritura
$output = fopen('php://output', 'w');

// Escribe el encabezado de las columnas en el archivo CSV
fputcsv($output, array('ID', 'Nombre Paciente', 'Nombre Especialista', 'Fecha y Hora Solicitada', 'Tipo de Cita', 'Aseguradora', 'Notas Adicionales', 'Motivo de Consulta'));

// Consulta para obtener los datos de las citas
$sql = "
    SELECT 
        Citas.id, 
        Pacientes.nombre_completo AS paciente_nombre, 
        Especialistas.nombre_completo AS especialista_nombre, 
        Citas.fecha_hora_solicitada, 
        Citas.tipo_cita, 
        Citas.aseguradora, 
        Citas.notas_adicionales, 
        Citas.motivo_consulta 
    FROM Citas
    JOIN Pacientes ON Citas.id_paciente = Pacientes.id
    JOIN Especialistas ON Citas.id_especialista = Especialistas.id
";

$result = $conn->query($sql);

// Escribe los datos de las citas en el archivo CSV
while ($row = $result->fetch_assoc()) {
    fputcsv($output, $row);
}

// Cierra el archivo
fclose($output);
exit();
?>
