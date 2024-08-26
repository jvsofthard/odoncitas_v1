<?php
include('includes/db.php');
include('includes/header.php');
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte de Ingresos por Especialista</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Reporte de Ingresos por Especialista</h2>
        <form action="generar_reporte_ingresos.php" method="post">
            <label for="id_especialista">Especialista:</label>
            <select id="id_especialista" name="id_especialista" required>
                <?php
                $result_especialistas = $conn->query("SELECT id, nombre_completo FROM Especialistas");
                while ($row = $result_especialistas->fetch_assoc()) {
                    echo "<option value='" . $row['id'] . "'>" . $row['nombre_completo'] . "</option>";
                }
                ?>
            </select><br>

            <label for="fecha_inicio">Fecha de Inicio:</label>
            <input type="date" id="fecha_inicio" name="fecha_inicio" required><br>

            <label for="fecha_fin">Fecha de Fin:</label>
            <input type="date" id="fecha_fin" name="fecha_fin" required><br>

            <input type="submit" value="Generar Reporte">
            <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
        </form>
    </div>
<?php
include('includes/footer.php');
?>
</body>
</html>
