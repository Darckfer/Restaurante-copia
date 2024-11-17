<?php
include_once '../procesos/conexion.php';

$idSala = isset($_GET['sala']) ? intval($_GET['sala']) : 0;

if ($idSala > 0) {
    $sql = "SELECT id_mesa FROM mesa WHERE id_sala = $idSala";
    $result = mysqli_query($conn, $sql);

    $mesas = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $mesas[] = $row;
    }

    echo json_encode($mesas);
} else {
    echo json_encode([]);
}

mysqli_close($conn);
