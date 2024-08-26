<?php
include('includes/db.php');

// Verifica si se ha pasado un ID de cita a través de la URL
if (isset($_GET['id'])) {
    $id_cita = $_GET['id'];

    // Configura el nombre del archivo CSV
    $filename = "detalle_cita_" . $id_cita . "_" . date("Y-m-d") . ".csv";

    // Establece el encabezado para la descarga del archivo
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=' . $filename);

    // Abre el archivo para escritura
    $output = fopen('php://output', 'w');

    // Escribe el encabezado de las columnas en el archivo CSV
    fputcsv($output, array('ID', 'Nombre Paciente', 'Nombre Especialista', 'Fecha y Hora Solicitada', 'Tipo de Cita', 'Aseguradora', 'Notas Adicionales', 'Motivo de Consulta'));

    // Consulta para obtener los detalles de la cita específica
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
        WHERE Citas.id = '$id_cita'
    ";

    $result = $conn->query($sql);

    // Escribe los datos de la cita en el archivo CSV
    if ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    } else {
        echo "Error: Cita no encontrada.";
    }

    // Cierra el archivo
    fclose($output);
    exit();
} else {
    echo "Error: ID de cita no proporcionado.";
    exit();
}
?>
