<?php
include('includes/db.php');
include('includes/header.php');

// Obtener el ID del pago desde la URL
$id_pago = $_GET['id'];

// Consulta para obtener los detalles del pago
$sql_pago = "SELECT Pagos.id, Pacientes.nombre_completo AS paciente, Citas.id AS cita_id, Citas.fecha_hora_solicitada, Pagos.monto, Pagos.fecha_pago, Pagos.metodo_pago, Pagos.notas
             FROM Pagos
             INNER JOIN Citas ON Pagos.id_cita = Citas.id
             INNER JOIN Pacientes ON Citas.id_paciente = Pacientes.id
             WHERE Pagos.id = ?";
$stmt = $conn->prepare($sql_pago);
$stmt->bind_param("i", $id_pago);
$stmt->execute();
$result = $stmt->get_result();
$pago = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle del Pago</title>
    <link rel="stylesheet" href="css/styles.css">
    <script>
        function printPage() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Detalle del Pago</h2>
        <?php if ($pago): ?>
            <p><strong>ID:</strong> <?php echo htmlspecialchars($pago['id']); ?></p>
            <p><strong>Paciente:</strong> <?php echo htmlspecialchars($pago['paciente']); ?></p>
            <p><strong>ID de Cita:</strong> <?php echo htmlspecialchars($pago['cita_id']); ?></p>
            <p><strong>Fecha y Hora de Cita:</strong> <?php echo htmlspecialchars($pago['fecha_hora_solicitada']); ?></p>
            <p><strong>Monto:</strong> <?php echo number_format($pago['monto'], 2); ?></p>
            <p><strong>Fecha de Pago:</strong> <?php echo htmlspecialchars($pago['fecha_pago']); ?></p>
            <p><strong>Método de Pago:</strong> <?php echo htmlspecialchars($pago['metodo_pago']); ?></p>
            <p><strong>Notas:</strong> <?php echo htmlspecialchars($pago['notas']); ?></p>
        <?php else: ?>
            <p>No se encontraron detalles para este pago.</p>
        <?php endif; ?>
        <button onclick="printPage()">Imprimir</button>
        <a href="listar_pagos.php" class="btn btn-secondary">Volver al Inicio</a>
    </div>
</body>
</html>

<?php
include('includes/footer.php');
?>
