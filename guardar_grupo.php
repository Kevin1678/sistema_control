<?php
// guardar_grupo.php
// Conexión a la base de datos
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sistema_control";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$nombre_grupo = $_POST['nombre_grupo'];
$descripcion = $_POST['descripcion'];

// Preparar la consulta SQL para insertar el nuevo grupo
$sql = "INSERT INTO Grupos (nombre_grupo, descripcion) VALUES ('$nombre_grupo', '$descripcion')";

// Ejecutar la consulta
if ($conn->query($sql) === TRUE) {
    echo "Grupo registrado correctamente.";
    // Redirigir a la página de listado de grupos (si lo deseas)
    header("Location: listar_grupos.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Cerrar la conexión
$conn->close();
?>
