<?php
include('includes/db.php');
include('includes/header.php');

// Lógica para exportar reportes (ejemplo para pagos)
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exportar Reportes</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Exportar Reportes</h2>
        <form action="exportar_reportes.php" method="post">
            <label for="tipo_reporte">Tipo de Reporte:</label>
            <select id="tipo_reporte" name="tipo_reporte" required>
                <option value="pagos">Pagos</option>
                <option value="citas">Citas</option>
                <option value="pacientes">Pacientes</option>
                <!-- Agrega más opciones si es necesario -->
            </select><br>

            <label for="formato">Formato:</label>
            <select id="formato" name="formato" required>
                <option value="csv">CSV</option>
                <option value="pdf">PDF</option>
            </select><br>

            <input type="submit" value="Exportar Reporte">
        </form>
    </div>
<?php
include('includes/footer.php');
?>
</body>
</html>
