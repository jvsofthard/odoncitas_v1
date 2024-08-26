<?php
include('includes/db.php');
include('includes/header.php');

// Consulta para obtener todos los especialistas
$sql = "SELECT * FROM Especialistas";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Especialistas</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Listado de Especialistas</h2>
          <!-- Enlace para exportar a CSV -->
        <a href="exportar_especialistas.php" class="btn-exportar">Exportar a CSV</a>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Completo</th>
                    <th>Especialidad</th>
                    <th>Contacto</th>
                    <th>Correo</th>
                    <th>Turno</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . htmlspecialchars($row['nombre_completo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['especialidad']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['contacto']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['correo']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['turno']) . "</td>";
                        echo "<td>
                            <a href='detalle_especialista.php?id=" . $row['id'] . "'>Detalles</a> |
                            <a href='editar_especialista.php?id=" . $row['id'] . "'>Editar</a> |
                            <a href='eliminar_especialista.php?id=" . $row['id'] . "'>Eliminar</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No se encontraron especialistas.</td></tr>";
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
