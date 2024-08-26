<?php
include('includes/db.php');
include('includes/header.php');

// Consultar pagos que no han sido facturados
$sql_pagos = "SELECT Pagos.id, Pacientes.nombre_completo AS paciente, Citas.id AS cita_id, Citas.fecha_hora_solicitada, Pagos.monto, Pagos.fecha_pago, Pagos.metodo_pago
              FROM Pagos
              INNER JOIN Citas ON Pagos.id_cita = Citas.id
              INNER JOIN Pacientes ON Citas.id_paciente = Pacientes.id
              WHERE Pagos.estado = 'Pendiente'"; // Solo mostrar pagos pendientes

$result_pagos = $conn->query($sql_pagos);



// Obtener el estado de filtro desde la URL o por defecto mostrar todos
$estado_filtro = isset($_GET['estado']) ? $_GET['estado'] : '';

// Consultar pagos según el estado
$sql_pagos = "SELECT Pagos.id, Pacientes.nombre_completo AS paciente, Citas.id AS cita_id, Citas.fecha_hora_solicitada, Pagos.monto, Pagos.fecha_pago, Pagos.metodo_pago, Pagos.estado
              FROM Pagos
              INNER JOIN Citas ON Pagos.id_cita = Citas.id
              INNER JOIN Pacientes ON Citas.id_paciente = Pacientes.id";
              
if ($estado_filtro) {
    $sql_pagos .= " WHERE Pagos.estado = '$estado_filtro'";
}

$result_pagos = $conn->query($sql_pagos);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Pagos</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        /* Estilos para el botón de impresión */
        .btn-print {
            background-color: ##007bff; /* Verde */
            color: white;
            padding: 10px 20px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 10px 0;
        }

        .btn-print:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function imprimirListado() {
            var printWindow = window.open('', '', 'height=600,width=800');
            printWindow.document.write('<html><head><title>Listado de Pagos</title>');
            printWindow.document.write('<link rel="stylesheet" href="css/styles.css" type="text/css">'); // Incluye los estilos CSS
            printWindow.document.write('</head><body >');
            printWindow.document.write(document.querySelector('.container').innerHTML);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.focus();
            printWindow.print();
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Listado de Pagos</h2>
        <button class="btn-print" onclick="imprimirListado()">Imprimir Listado</button>
        <div>
            <a href="listar_pagos.php">Mostrar Todos</a> | 
            <a href="listar_pagos.php?estado=Pendiente">Mostrar Pendientes</a> | 
            <a href="listar_pagos.php?estado=Facturado">Mostrar Facturados</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Cita ID</th>
                    <th>Fecha y Hora de Cita</th>
                    <th>Monto</th>
                    <th>Fecha de Pago</th>
                    <th>Método de Pago</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_pagos->num_rows > 0) {
                    while ($row = $result_pagos->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['paciente'] . "</td>";
                        echo "<td>" . $row['cita_id'] . "</td>";
                        echo "<td>" . $row['fecha_hora_solicitada'] . "</td>";
                        echo "<td>" . $row['monto'] . "</td>";
                        echo "<td>" . $row['fecha_pago'] . "</td>";
                        echo "<td>" . $row['metodo_pago'] . "</td>";
                        echo "<td>" . $row['estado'] . "</td>";
                        echo "<td>
                                <a href='detalle_pago.php?id=" . $row['id'] . "'>Ver</a> | 
                                <a href='editar_pago.php?id=" . $row['id'] . "'>Editar</a> | 
                                <a href='eliminar_pago.php?id=" . $row['id'] . "' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este pago?\")'>Eliminar</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No se han encontrado pagos.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="index.php" class="btn btn-secondary">Volver al Inicio</a>
    </div>
</body>
</html>

<?php
include('includes/footer.php');
?>
