<?php
$servername = "localhost";   // Nombre del servidor, generalmente "localhost"
$username = "root";          // Nombre de usuario de la base de datos
$password = "";              // Contraseña de la base de datos
$dbname = "odontologia";     // Nombre de la base de datos

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {

}
?>
