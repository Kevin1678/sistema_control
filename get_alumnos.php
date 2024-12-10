<?php
include 'db_config.php';

if (isset($_POST['id_grupo'])) {
    $id_grupo = $_POST['id_grupo'];
    // Consulta para obtener los alumnos del grupo seleccionado
    $sql = "SELECT * FROM Alumnos WHERE id_grupo = $id_grupo";
    $result = $conn->query($sql);

    // Enviar los alumnos en formato JSON
    $alumnos = [];
    while ($row = $result->fetch_assoc()) {
        $alumnos[] = $row;
    }
    echo json_encode($alumnos);
}
?>
