<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar si se seleccionaron alumnos para registrar asistencia
    if (isset($_POST['asistencia']) && !empty($_POST['asistencia'])) {
        $asistencias = $_POST['asistencia']; // Array con los IDs de los alumnos que asistieron
        $id_grupo = $_POST['id_grupo']; // Obtener el grupo seleccionado
        $fecha_actual = date('Y-m-d'); // Fecha actual

        // Registrar asistencia en la base de datos
        foreach ($asistencias as $id_alumno => $value) {
            $sql = "INSERT INTO Asistencia (IdAlumno, Fecha, Asistio) VALUES ($id_alumno, '$fecha_actual', 1)";
            if (!$conn->query($sql)) {
                echo "Error al registrar asistencia para el alumno con ID $id_alumno: " . $conn->error;
            }
        }

        // Mensaje de confirmación y redirección
        echo "<script>
                alert('Asistencia guardada con éxito.');
                window.location.href = 'registrar_asistencia.php';
              </script>";
    } else {
        // No se seleccionó ningún alumno
        echo "<script>
                alert('No se seleccionó ningún alumno para la asistencia.');
                window.location.href = 'registrar_asistencia.php';
              </script>";
    }
} else {
    header("Location: registrar_asistencia.php");
    exit();
}
?>

