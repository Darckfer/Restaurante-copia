<?php
include_once '../procesos/conexion.php';

$idReserva = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($idReserva > 0) {
    $sqlDelete = "DELETE FROM reserva WHERE id_reserva = $idReserva";
    if (mysqli_query($conn, $sqlDelete)) {
        header("Location: ../reservas.php");
    } else {
        echo "Error al eliminar la reserva: " . mysqli_error($conn);
    }
} else {
    header("Location: index_reservas.php?msg=ID de reserva no válido");
}

mysqli_close($conn);
