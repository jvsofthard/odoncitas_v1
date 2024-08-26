<?php
include('includes/db.php');
include('includes/header.php');

$id = $_GET['id']; // Obtener el ID del paciente desde la URL

// Obtener los filtros de la solicitud (si existen)
$fecha_filtro = isset($_GET['fecha']) ? $_GET['fecha'] : '';
$tipo_cita_filtro = isset($_GET['tipo_cita']) ? $_GET['tipo_cita'] : '';
$especialista_filtro = isset($_GET['especialista']) ? $_GET['especialista'] : '';




// Codigo exportar CSV

// Verificar si se solicitó la exportación
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    // Consultar el paciente
    $sql = "SELECT * FROM Pacientes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $paciente = $result->fetch_assoc();

        // Crear el archivo CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=detalles_paciente.csv');
    
    $output = fopen('php://output', 'w');
    // Escribir los datos del paciente
    fputcsv($output, array('ID', 'Nombre Completo', 'Fecha de Nacimiento', 'Edad', 'Sexo', 'Nacionalidad', 'Dirección', 'Contacto', 'Correo Electrónico', 'Aseguradora', 'Número de Seguridad Social'));
    fputcsv($output, array($paciente['id'], $paciente['nombre_completo'], $paciente['fecha_nacimiento'], $paciente['edad'], $paciente['sexo'], $paciente['nacionalidad'], $paciente['direccion'], $paciente['contacto'], $paciente['correo_electronico'], $paciente['aseguradora'], $paciente['numero_seguridad_social']));
    
    // Escribir las citas asociadas
    fputcsv($output, array('ID Cita', 'Fecha y Hora', 'Tipo de Cita', 'Motivo de la Consulta', 'Notas Adicionales', 'Especialista', 'Especialidad'));
    while ($cita = $result_citas->fetch_assoc()) {
        fputcsv($output, array($cita['id'], $cita['fecha_hora'], $cita['tipo_cita'], $cita['motivo_consulta'], $cita['notas_adicionales'], $cita['especialista_nombre'], $cita['especialidad']));
    }
    fclose($output);
    exit;
}

// fin codigo exportar CSV




// Consultar los detalles del paciente
$sql = "SELECT * FROM Pacientes WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$paciente = $result->fetch_assoc();

if (!$paciente) {
    echo "<p>Paciente no encontrado.</p>";
    exit;
}

// Construir la consulta de citas con filtros
$sql_citas = "
    SELECT Citas.*, Especialistas.nombre_completo AS especialista_nombre, Especialistas.especialidad 
    FROM Citas 
    LEFT JOIN Especialistas ON Citas.id_especialista = Especialistas.id 
    WHERE Citas.id_paciente = ?";

$params = [$id];
$types = "i";

if (!empty($fecha_filtro)) {
    $sql_citas .= " AND Citas.fecha_hora LIKE ?";
    $params[] = "%$fecha_filtro%";
    $types .= "s";
}

if (!empty($tipo_cita_filtro)) {
    $sql_citas .= " AND Citas.tipo_cita = ?";
    $params[] = $tipo_cita_filtro;
    $types .= "s";
}

if (!empty($especialista_filtro)) {
    $sql_citas .= " AND Especialistas.nombre_completo LIKE ?";
    $params[] = "%$especialista_filtro%";
    $types .= "s";
}

$stmt_citas = $conn->prepare($sql_citas);
$stmt_citas->bind_param($types, ...$params);
$stmt_citas->execute();
$result_citas = $stmt_citas->get_result();
?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalles del Paciente</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Detalles del Paciente</h2>
        <a href="detalle_paciente.php?id=<?php echo htmlspecialchars($id); ?>&export=csv" class="btn btn-primary">Exportar a CSV</a>
        <table>
            <tr>
                <th>Nombre Completo:</th>
                <td><?php echo htmlspecialchars($paciente['nombre_completo']); ?></td>
            </tr>
            <!-- Agregar el resto de los campos aquí -->
        </table>

        <h3>Citas Asociadas</h3>
        <form method="GET" action="">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
            
            <div>
                <label for="tipo_cita">Tipo de Cita:</label>
                <input type="text" id="tipo_cita" name="tipo_cita" value="<?php echo htmlspecialchars($tipo_cita_filtro); ?>">
            </div>
            <div>
                <label for="especialista">Especialista:</label>
                <input type="text" id="especialista" name="especialista" value="<?php echo htmlspecialchars($especialista_filtro); ?>">
            </div>
            <button type="submit">Filtrar</button>
        </form>

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <!--<th>Fecha y Hora</th> -->
                    <th>Tipo de Cita</th>
                    <th>Motivo de la Consulta</th>
                    <th>Notas Adicionales</th>
                    <th>Especialista</th>
                    <th>Especialidad</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_citas->num_rows > 0) {
                    while($cita = $result_citas->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($cita['id']) . "</td>";
                       // echo "<td>" . htmlspecialchars($cita['fecha_hora']) . "</td>";
                        echo "<td>" . htmlspecialchars($cita['tipo_cita']) . "</td>";
                        echo "<td>" . htmlspecialchars($cita['motivo_consulta']) . "</td>";
                        echo "<td>" . htmlspecialchars($cita['notas_adicionales']) . "</td>";
                        echo "<td>" . htmlspecialchars($cita['especialista_nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($cita['especialidad']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay citas asociadas para este paciente.</td></tr>";
                }
                ?>
            </tbody>
        </table>

         <a href="listar_pacientes.php" class="btn btn-secondary">Volver al Listado</a>
    </div>

<?php
include('includes/footer.php');
?>
</body>
</html>
