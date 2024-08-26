<?php
include('includes/db.php');
include('includes/header.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Citas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Listado de Citas</h2>
        <!-- Enlace para exportar a CSV -->
        <a href="exportar_citas.php" class="btn-exportar">Exportar a CSV</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Especialista</th>
                    <th>Fecha y Hora</th>
                    <th>Tipo de Cita</th>
                    <th>Aseguradora</th>
                    <th>Motivo de la Consulta</th>
                    <th>Notas Adicionales</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Consulta para obtener todas las citas con la información del paciente y especialista
                $sql = "
                    SELECT Citas.id, Pacientes.nombre_completo AS paciente_nombre, Especialistas.nombre_completo AS especialista_nombre, 
                           Citas.fecha_hora_solicitada, Citas.tipo_cita, Citas.aseguradora, Citas.motivo_consulta, Citas.notas_adicionales
                    FROM Citas
                    JOIN Pacientes ON Citas.id_paciente = Pacientes.id
                    JOIN Especialistas ON Citas.id_especialista = Especialistas.id
                    ORDER BY Citas.fecha_hora_solicitada DESC";
                
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['paciente_nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['especialista_nombre']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['fecha_hora_solicitada']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['tipo_cita']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['aseguradora']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['motivo_consulta']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['notas_adicionales']) . "</td>";
                        echo "<td>";
                        echo "<a href='detalle_cita.php?id=" . htmlspecialchars($row['id']) . "'>Ver</a> | ";
                        echo "<a href='editar_cita.php?id=" . htmlspecialchars($row['id']) . "'>Editar</a> | ";
                        echo "<a href='eliminar_cita.php?id=" . htmlspecialchars($row['id']) . "' onclick=\"return confirm('¿Estás seguro de que deseas eliminar esta cita?');\">Eliminar</a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='9'>No se encontraron citas.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="crear_citas.php" class="btn btn-primary">Crear Nueva Cita</a>
        <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
    </div>
<?php
include('includes/footer.php');
?>

</body>
</html>
