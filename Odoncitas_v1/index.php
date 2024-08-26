<?php
include('includes/db.php');
include('includes/header.php');

// Obtener citas próximas para esta semana
$sql_citas_proximas = "SELECT Citas.id, Pacientes.nombre_completo AS paciente, Citas.fecha_hora_solicitada, Especialistas.nombre_completo AS especialista
                       FROM Citas
                       INNER JOIN Pacientes ON Citas.id_paciente = Pacientes.id
                       INNER JOIN Especialistas ON Citas.id_especialista = Especialistas.id
                       WHERE Citas.fecha_hora_solicitada BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 1 WEEK)
                       ORDER BY Citas.fecha_hora_solicitada";
$result_citas_proximas = $conn->query($sql_citas_proximas);



// Obtener pagos pendientes
$sql_pagos_pendientes = "SELECT Pagos.id, Pacientes.nombre_completo AS paciente, Citas.id AS cita_id, Citas.fecha_hora_solicitada, Pagos.monto, Pagos.fecha_pago, Pagos.metodo_pago, Pagos.notas
                         FROM Pagos
                         INNER JOIN Citas ON Pagos.id_cita = Citas.id
                         INNER JOIN Pacientes ON Citas.id_paciente = Pacientes.id
                         WHERE Pagos.fecha_pago IS NULL OR Pagos.fecha_pago > CURDATE()
                         ORDER BY Citas.fecha_hora_solicitada";
$result_pagos_pendientes = $conn->query($sql_pagos_pendientes);


// Obtener datos para el resumen general
$sql_total_pacientes = "SELECT COUNT(*) AS total_pacientes FROM Pacientes";
$sql_total_citas = "SELECT COUNT(*) AS total_citas FROM Citas";
$sql_total_especialistas = "SELECT COUNT(*) AS total_especialistas FROM Especialistas";
$sql_total_ingresos = "
    SELECT SUM(Pagos.monto) AS total_ingresos
    FROM Pagos
";

$total_pacientes = $conn->query($sql_total_pacientes)->fetch_assoc()['total_pacientes'];
$total_citas = $conn->query($sql_total_citas)->fetch_assoc()['total_citas'];
$total_especialistas = $conn->query($sql_total_especialistas)->fetch_assoc()['total_especialistas'];
$total_ingresos = $conn->query($sql_total_ingresos)->fetch_assoc()['total_ingresos'];

// Consultas para los gráficos
$sql_ingresos = "
    SELECT 
        DATE_FORMAT(p.fecha_pago, '%Y-%m') AS mes,
        SUM(p.monto) AS total_ingresos
    FROM 
        Pagos p
    INNER JOIN 
        Citas c ON p.id_cita = c.id
    WHERE 
        p.fecha_pago BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW()
    GROUP BY 
        DATE_FORMAT(p.fecha_pago, '%Y-%m')
    ORDER BY 
        mes
";
$result_ingresos = $conn->query($sql_ingresos);
$ingresos_meses = [];
$ingresos_totales = [];
while ($row = $result_ingresos->fetch_assoc()) {
    $ingresos_meses[] = $row['mes'];
    $ingresos_totales[] = (float)$row['total_ingresos'];
}

$sql_citas = "
    SELECT 
        DATE_FORMAT(c.fecha_hora_solicitada, '%Y-%m') AS mes,
        COUNT(c.id) AS total_citas
    FROM 
        Citas c
    WHERE 
        c.fecha_hora_solicitada BETWEEN DATE_SUB(NOW(), INTERVAL 1 YEAR) AND NOW()
    GROUP BY 
        DATE_FORMAT(c.fecha_hora_solicitada, '%Y-%m')
    ORDER BY 
        mes
";
$result_citas = $conn->query($sql_citas);
$citas_meses = [];
$citas_totales = [];
while ($row = $result_citas->fetch_assoc()) {
    $citas_meses[] = $row['mes'];
    $citas_totales[] = (int)$row['total_citas'];
}

$sql_pagos = "
    SELECT 
        metodo_pago,
        COUNT(*) AS total_pagos
    FROM 
        Pagos
    GROUP BY 
        metodo_pago
";
$result_pagos = $conn->query($sql_pagos);
$metodos_pago = [];
$total_pagos = [];
while ($row = $result_pagos->fetch_assoc()) {
    $metodos_pago[] = $row['metodo_pago'];
    $total_pagos[] = (int)$row['total_pagos'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Resumen del Sistema</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Resumen General del Sistema</h1>
        
        <!-- Resumen General -->
        <div class="summary">
            <div class="summary-item">
                <h2>Total de Pacientes</h2>
                <p><?php echo number_format($total_pacientes); ?></p>
            </div>
            <div class="summary-item">
                <h2>Total de Citas</h2>
                <p><?php echo number_format($total_citas); ?></p>
            </div>
        <!--
            <div class="summary-item">
                <h2>Total de Especialistas</h2>
                <p><?php echo number_format($total_especialistas); ?></p>
            </div>
        -->
            <div class="summary-item">
                <h2>Total de Ingresos</h2>
                <p><?php echo number_format($total_ingresos, 2); ?> RD$</p>
            </div>
        </div>
        
<!-- Citas Próximas -->
        <div class="section">
            <h2>Citas Próximas</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Cita</th>
                        <th>Paciente</th>
                        <th>Fecha y Hora</th>
                        <th>Especialista</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_citas_proximas->num_rows > 0): ?>
                        <?php while ($row = $result_citas_proximas->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['paciente']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_hora_solicitada']); ?></td>
                                <td><?php echo htmlspecialchars($row['especialista']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No hay citas próximas para esta semana.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>


<!-- Pagos Pendientes -->
        <div class="section">
            <h2>Pagos Pendientes</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Pago</th>
                        <th>Paciente</th>
                        <th>No. Cita</th>
                        <th>Fecha y Hora de Cita</th>
                        <th>Monto</th>
                        <th>Fecha de Pago</th>
                        <th>Método de Pago</th>
                        <th>Notas</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_pagos_pendientes->num_rows > 0): ?>
                        <?php while ($row = $result_pagos_pendientes->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['paciente']); ?></td>
                                <td><?php echo htmlspecialchars($row['cita_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['fecha_hora_solicitada']); ?></td>
                                <td><?php echo number_format($row['monto'], 2); ?></td>
                                <td><?php echo $row['fecha_pago'] ? htmlspecialchars($row['fecha_pago']) : 'Pendiente'; ?></td>
                                <td><?php echo htmlspecialchars($row['metodo_pago']); ?></td>
                                <td><?php echo htmlspecialchars($row['notas']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8">No hay pagos pendientes.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>



        <!-- Gráficos -->
        <div class="charts">
            <div class="chart-container">
                <h2>Ingresos Mensuales</h2>
                <canvas id="incomeChart"></canvas>
            </div>
            <div class="chart-container">
                <h2>Citas por Mes</h2>
                <canvas id="appointmentsChart"></canvas>
            </div>
            <div class="chart-container">
                <h2>Distribución de Pagos</h2>
                <canvas id="paymentsChart"></canvas>
            </div>
        </div>

        <!-- JavaScript para los gráficos -->
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Datos de ingresos
                const incomeData = {
                    labels: <?php echo json_encode($ingresos_meses); ?>,
                    datasets: [{
                        label: 'Ingresos',
                        data: <?php echo json_encode($ingresos_totales); ?>,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                };

                // Datos de citas
                const appointmentsData = {
                    labels: <?php echo json_encode($citas_meses); ?>,
                    datasets: [{
                        label: 'Citas',
                        data: <?php echo json_encode($citas_totales); ?>,
                        backgroundColor: 'rgba(153, 102, 255, 0.2)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                };

                // Datos de distribución de pagos
                const paymentsData = {
                    labels: <?php echo json_encode($metodos_pago); ?>,
                    datasets: [{
                        label: 'Distribución de Pagos',
                        data: <?php echo json_encode($total_pagos); ?>,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                };

                // Configuración del gráfico de ingresos
                const ctxIncome = document.getElementById('incomeChart').getContext('2d');
                new Chart(ctxIncome, {
                    type: 'line', // o 'bar' para gráfico de barras
                    data: incomeData,
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Configuración del gráfico de citas
                const ctxAppointments = document.getElementById('appointmentsChart').getContext('2d');
                new Chart(ctxAppointments, {
                    type: 'bar',
                    data: appointmentsData,
                    options: {
                        responsive: true,
                        scales: {
                            x: {
                                beginAtZero: true
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Configuración del gráfico de distribución de pagos
                const ctxPayments = document.getElementById('paymentsChart').getContext('2d');
                new Chart(ctxPayments, {
                    type: 'pie',
                    data: paymentsData,
                    options: {
                        responsive: true
                    }
                });
            });
        </script>
    </div>
    <?php include('includes/footer.php'); ?>
</body>
</html>
