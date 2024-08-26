<?php
include('includes/db.php');
include('includes/header.php');

// Obtener el ID de la factura desde la URL
$id_factura = $_GET['id'];

// Obtener los detalles de la factura
$sql = "SELECT Facturas.*, Pagos.*, Pacientes.nombre_completo AS paciente, Citas.id AS cita_id
        FROM Facturas
        INNER JOIN Pagos ON Facturas.id_pago = Pagos.id
        INNER JOIN Pacientes ON Pagos.id_paciente = Pacientes.id
        INNER JOIN Citas ON Pagos.id_cita = Citas.id
        WHERE Facturas.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_factura);
$stmt->execute();
$result = $stmt->get_result();
$factura = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle de Factura</title>
    <link rel="stylesheet" href="css/styles.css">
    <script>
        function printInvoice() {
            window.print();
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Detalle de Factura</h2>
        <button onclick="printInvoice()">Imprimir Factura</button>
        <table>
            <tr>
                <th>ID Factura:</th>
                <td><?php echo htmlspecialchars($factura['id']); ?></td>
            </tr>
            <tr>
                <th>Fecha de Factura:</th>
                <td><?php echo htmlspecialchars($factura['fecha_factura']); ?></td>
            </tr>
            <tr>
                <th>Monto:</th>
                <td><?php echo number_format($factura['monto'], 2); ?></td>
            </tr>
            <tr>
                <th>Método de Pago:</th>
                <td><?php echo htmlspecialchars($factura['metodo_pago']); ?></td>
            </tr>
            <tr>
                <th>Paciente:</th>
                <td><?php echo htmlspecialchars($factura['paciente']); ?></td>
            </tr>
            <tr>
                <th>Cita:</th>
                <td><?php echo htmlspecialchars($factura['cita_id']); ?></td>
            </tr>
            <tr>
                <th>Notas:</th>
                <td><?php echo htmlspecialchars($factura['notas']); ?></td>
            </tr>
        </table>
        <a href="listar_facturas.php" class="btn btn-secondary">Volver al Listado</a>
    </div>
<?php
include('includes/footer.php');
?>
</body>
</html>
