<?php
session_start();
include_once './conexion.php';

$id_tipoSala = mysqli_real_escape_string($conn, trim($_POST['id_tipoSala']));
$idCamarero = mysqli_real_escape_string($conn, trim($_SESSION['id_camarero']));
$idSala = mysqli_real_escape_string($conn, trim($_POST['id_sala']));
$idMesa = mysqli_real_escape_string($conn, trim($_POST['id_mesa']));
$num_sillas = mysqli_real_escape_string($conn, trim($_POST['num_sillas']));
$num_sillas_real = mysqli_real_escape_string($conn, trim($_POST['num_sillas_real']));
$fecha_inicio = mysqli_real_escape_string($conn, trim($_POST['fecha_inicio']));
$fecha_fin = mysqli_real_escape_string($conn, trim($_POST['fecha_fin']));

$sqlCheck = "SELECT * FROM reserva WHERE id_mesa = ? AND ((fecha_inicio BETWEEN ? AND ?) OR (fecha_fin BETWEEN ? AND ?) OR (? BETWEEN fecha_inicio AND fecha_fin) OR (? BETWEEN fecha_inicio AND fecha_fin))";

$stmtCheck = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmtCheck, $sqlCheck);
mysqli_stmt_bind_param($stmtCheck, 'issssss', $idMesa, $fecha_inicio, $fecha_fin, $fecha_inicio, $fecha_fin, $fecha_inicio, $fecha_fin);
mysqli_stmt_execute($stmtCheck);
$resultCheck = mysqli_stmt_get_result($stmtCheck);

if (mysqli_num_rows($resultCheck) > 0) {
    echo "Ya existe una reserva para esta mesa en ese rango de fechas o horas.";
    mysqli_stmt_close($stmtCheck);
    mysqli_close($conn);
    exit();
}

try {
    $sqlInsert = "INSERT INTO reserva (id_mesa, id_camarero, num_sillas, fecha_inicio, fecha_fin) VALUES (?, ?, ?, ?, ?)";
    $stmtInsert = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmtInsert, $sqlInsert);
    mysqli_stmt_bind_param($stmtInsert, 'iiiss', $idMesa, $idCamarero, $num_sillas, $fecha_inicio, $fecha_fin);
    mysqli_stmt_execute($stmtInsert);

    mysqli_commit($conn);
    echo "Reserva realizada con éxito.";

    mysqli_stmt_close($stmtInsert);
    mysqli_close($conn);
} catch (Exception $e) {
    mysqli_rollback($conn);
    echo "Error: " . $e->getMessage();
    mysqli_close($conn);
    exit();
}
