<?php
include('includes/db.php');
include('includes/header.php');

// CODIGO PARA EXPORTAR CSV
// Verificar si se solicitó la exportación
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    // Consultar todos los pacientes
    $sql = "SELECT * FROM Pacientes";
    $result = $conn->query($sql);

    // Crear el archivo CSV
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment;filename=pacientes.csv');
    
    $output = fopen('php://output', 'w');
    // Escribir los encabezados de columna
    fputcsv($output, array('ID', 'Nombre Completo', 'Fecha de Nacimiento', 'Edad', 'Sexo', 'Nacionalidad', 'Dirección', 'Contacto', 'Correo Electrónico', 'Aseguradora', 'Número de Seguridad Social'));
    
    // Escribir los datos de los pacientes
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Consulta de pacientes para la vista
$sql = "SELECT * FROM Pacientes";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Pacientes</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Listado de Pacientes</h2>
 <a href="listar_pacientes.php?export=csv" class="btn btn-primary">Exportar a CSV</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Fecha de Nacimiento</th>
                    <th>Edad</th>
                    <th>Contacto</th>
                    <th>Correo Electrónico</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Obtener los pacientes de la base de datos
                $sql = "SELECT id, nombre_completo, fecha_nacimiento, edad, contacto, correo_electronico FROM Pacientes";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Salida de datos de cada fila
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['nombre_completo'] . "</td>";
                        echo "<td>" . $row['fecha_nacimiento'] . "</td>";
                        echo "<td>" . $row['edad'] . "</td>";
                        echo "<td>" . $row['contacto'] . "</td>";
                        echo "<td>" . $row['correo_electronico'] . "</td>";
                        echo "<td>
                                <a href='detalle_paciente.php?id=" . $row['id'] . "'>Ver</a> |
                                <a href='editar_paciente.php?id=" . $row['id'] . "'>Editar</a> |
                                <a href='eliminar_paciente.php?id=" . $row['id'] . "' onclick=\"return confirm('¿Está seguro de que desea eliminar este paciente?');\">Eliminar</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No se encontraron pacientes</td></tr>";
                }
                ?>
            </tbody>
        </table>
         <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
    </div>
<?php
include('includes/footer.php');
?>

</body>
</html>
