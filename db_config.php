<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "sistema_control";

// Crear conexión
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
