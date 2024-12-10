<?php
include 'db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_grupo'])) {
    $id_grupo = $_POST['id_grupo'];

    // Consultar datos del grupo y sus alumnos, incluyendo el cálculo del promedio
    $query = "
        SELECT A.Nombre, A.Apellido, A.Matricula, 
            (SELECT COUNT(*) FROM Asistencia WHERE IdAlumno = A.id AND Asistio = 1) AS TotalAsistencias,
            AVG(CASE WHEN C.unidad1 IS NOT NULL THEN (C.unidad1 + C.unidad2 + C.unidad3 + C.unidad4) / 4 ELSE 0 END) AS PromedioGeneral,
            CASE 
                WHEN AVG(CASE WHEN C.unidad1 IS NOT NULL THEN (C.unidad1 + C.unidad2 + C.unidad3 + C.unidad4) / 4 ELSE 0 END) >= 60 
                THEN 'Aprobado' 
                ELSE 'Reprobado' 
            END AS Estatus
        FROM Alumnos A
        LEFT JOIN Calificaciones C ON A.id = C.id_alumno
        WHERE A.id_grupo = ?
        GROUP BY A.id
    ";

    // Preparar la consulta
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_grupo);
    $stmt->execute();
    $result = $stmt->get_result();

    // Comprobar si hay datos
    if ($result->num_rows > 0) {
        // Preparar archivo Excel
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=reporte_grupo_$id_grupo.xls");

        // Imprimir los encabezados de las columnas
        echo "Nombre\tApellido\tMatrícula\tTotal Asistencias\tPromedio General\tEstatus\n";

        // Imprimir los datos
        while ($row = $result->fetch_assoc()) {
            echo "{$row['Nombre']}\t{$row['Apellido']}\t{$row['Matricula']}\t";
            echo "{$row['TotalAsistencias']}\t" . number_format($row['PromedioGeneral'], 2) . "\t{$row['Estatus']}\n";
        }
    } else {
        echo "No se encontraron datos para este grupo.";
    }

    exit;
}
?>



