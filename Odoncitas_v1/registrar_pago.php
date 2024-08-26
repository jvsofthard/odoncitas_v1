<?php
include('includes/db.php');
include('includes/header.php');

// Obtener pacientes de la base de datos
$sql_pacientes = "SELECT id, nombre_completo FROM Pacientes";
$result_pacientes = $conn->query($sql_pacientes);

// Obtener citas de la base de datos
$sql_citas = "SELECT Citas.id, Pacientes.nombre_completo AS paciente, Citas.fecha_hora_solicitada 
              FROM Citas 
              INNER JOIN Pacientes ON Citas.id_paciente = Pacientes.id";
$result_citas = $conn->query($sql_citas);
?>




<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registrar Pago</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <h2>Registrar Pago</h2>
        <form action="procesar_pago.php" method="post">
            <label for="id_cita">Cita:</label>
            <select id="id_cita" name="id_cita" required>
                <?php
                if ($result_citas->num_rows > 0) {
                    while ($row = $result_citas->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['paciente'] . " - " . $row['fecha_hora_solicitada'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay citas disponibles</option>";
                }
                ?>
            </select><br>

            <label for="id_paciente">Paciente:</label>
            <select id="id_paciente" name="id_paciente" required>
                <?php
                if ($result_pacientes->num_rows > 0) {
                    while ($row = $result_pacientes->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['nombre_completo'] . "</option>";
                    }
                } else {
                    echo "<option value=''>No hay pacientes disponibles</option>";
                }
                ?>
            </select><br>

            <label for="monto">Monto:</label>
            <input type="number" id="monto" name="monto" step="0.01" required><br>

            <label for="fecha_pago">Fecha de Pago:</label>
            <input type="date" id="fecha_pago" name="fecha_pago" required><br>

            <label for="metodo_pago">Método de Pago:</label>
            <select id="metodo_pago" name="metodo_pago" required>
                <option value="Efectivo">Efectivo</option>
                <option value="Tarjeta de Crédito">Tarjeta de Crédito</option>
                <option value="Transferencia">Transferencia</option>
                <option value="Otro">Otro</option>
            </select><br>

            <label for="notas">Notas:</label>
            <textarea id="notas" name="notas"></textarea><br>

            <input type="submit" value="Registrar Pago">
            <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
        </form>
    </div>

    <script>
    document.querySelector('form').addEventListener('submit', function(event) {
        event.preventDefault(); // Evitar el envío normal del formulario
        
        const formData = new FormData(this);

        fetch('procesar_pago.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if (data.trim() === 'success') {
                alert('Pago registrado exitosamente.');
                // Aquí podrías redirigir si lo deseas, o limpiar el formulario
                window.location.reload(); // Para reiniciar el formulario, si no deseas recargar quita esta línea
            } else {
                alert('Hubo un error al registrar el pago. Inténtalo de nuevo.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un error en el sistema. Inténtalo de nuevo.');
        });
    });
</script>
</body>
</html>

<?php
include('includes/footer.php');
?>
