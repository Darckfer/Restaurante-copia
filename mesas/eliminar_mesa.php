<?php
include_once '../procesos/conexion.php';

$idCamarero = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idCamarero > 0) {
    $sql = "DELETE FROM mesa WHERE id_mesa = $idCamarero";
    if (mysqli_query($conn, $sql)) {
        header("Location: index.php");
        exit();
    }
}

mysqli_close($conn);
