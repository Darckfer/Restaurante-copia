<?php
// session_start();
require_once "../procesos/conexion.php";
if (isset($_GET['orden']) && ($_GET['orden'] == "asc" || $_GET['orden'] == "desc")) {
    // Recoge si viene orden por method GET
    $orden = mysqli_real_escape_string($conn, $_GET['orden']);
    if(isset($_SESSION['camarero'])){
        try{
            $camarero = mysqli_real_escape_string($conn, trim($_SESSION['camarero']));
            // Consulta para ver lo sumativo de lo que ha hecho camarero
            $sqlOrden = "SELECT c.nombre, tp.tipo_sala, s.nombre_sala, m.id_mesa, h.hora_inicio, h.hora_fin FROM historial h INNER JOIN camarero c ON h.id_camarero = c.id_camarero INNER JOIN mesa m ON m.id_mesa = h.id_mesa INNER JOIN sala s ON s.id_sala = m.id_sala INNER JOIN tipo_sala tp ON tp.id_tipoSala = s.id_tipoSala WHERE c.id_camarero = ? ORDER BY h.hora_inicio $orden";
            $stmtOrden = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmtOrden, $sqlOrden);
            mysqli_stmt_bind_param($stmtOrden, "i",$camarero );
        }catch(PDOException $e){
            echo "Error: ".$e->getMessage();
        }
    } else {
        // Consulta para ordenar por orden de hora_inicio la tabla
        $sqlOrden = "SELECT c.nombre, tp.tipo_sala, s.nombre_sala, m.id_mesa, h.hora_inicio, h.hora_fin FROM historial h INNER JOIN camarero c ON h.id_camarero = c.id_camarero INNER JOIN mesa m ON m.id_mesa = h.id_mesa INNER JOIN sala s ON s.id_sala = m.id_sala INNER JOIN tipo_sala tp ON tp.id_tipoSala = s.id_tipoSala ORDER BY h.hora_inicio $orden";
        $stmtOrden = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmtOrden, $sqlOrden);
    }
    mysqli_stmt_execute($stmtOrden);
    $result = mysqli_stmt_get_result($stmtOrden);
} else if (isset($_GET['camarero'])) {
    //Recoge lo que viene por GET el id de camarero
    $camarero = mysqli_real_escape_string($conn, trim($_GET['camarero']));
    // Consulta para ver todos las ocupaciones que ha hecho un camarero
    $sqlCamarero = "SELECT c.id_camarero, c.nombre, tp.tipo_sala, s.nombre_sala, m.id_mesa, h.hora_inicio, h.hora_fin FROM historial h INNER JOIN camarero c ON h.id_camarero = c.id_camarero INNER JOIN mesa m ON m.id_mesa = h.id_mesa INNER JOIN sala s ON s.id_sala = m.id_sala INNER JOIN tipo_sala tp On tp.id_tipoSala = s.id_tipoSala WHERE c.id_camarero = ?";
    $stmtCamarero = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmtCamarero, $sqlCamarero);
    mysqli_stmt_bind_param($stmtCamarero, "i", $camarero);
    mysqli_stmt_execute($stmtCamarero);
    $result = mysqli_stmt_get_result($stmtCamarero);
    $_SESSION['camarero'] = htmlspecialchars_decode($_GET['camarero']); // Se guarda para que sea sumativo

} else if (isset($_GET['tipoSala'])) {
    // Recoge el valor tipoSala por por method GET
    $tipoSala = mysqli_real_escape_string($conn, trim($_GET['tipoSala']));
    $_SESSION['tipoSala'] = htmlspecialchars($tipoSala);
    // Consulta para filtrar por tipo de sala la tabla
    if (!isset($_SESSION['camarero'])) {
        // Consulta para ver lo sumativo de lo que ha hecho camarero
        $sqlTipoSala = "SELECT c.nombre, tp.tipo_sala, s.nombre_sala, m.id_mesa, h.hora_inicio, h.hora_fin, tp.id_tipoSala FROM historial h INNER JOIN camarero c ON h.id_camarero = c.id_camarero INNER JOIN mesa m ON m.id_mesa = h.id_mesa INNER JOIN sala s ON s.id_sala = m.id_sala INNER JOIN tipo_sala tp On tp.id_tipoSala = s.id_tipoSala WHERE tp.id_tipoSala = ?";
        $stmtTipoSala = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmtTipoSala, $sqlTipoSala);
        mysqli_stmt_bind_param($stmtTipoSala, "i", $tipoSala);
    } else {
        // Consulta para ver todas las los datos de del camareror seleccionado anteriormente, con el fin de las ocupaciones y desocupa que realizo solo el camarero seleccionado
        $camarero = mysqli_real_escape_string($conn, trim($_SESSION['camarero']));
        $sqlTipoSala = "SELECT c.nombre, tp.tipo_sala, s.nombre_sala, m.id_mesa, h.hora_inicio, h.hora_fin, tp.id_tipoSala FROM historial h INNER JOIN camarero c ON h.id_camarero = c.id_camarero INNER JOIN mesa m ON m.id_mesa = h.id_mesa INNER JOIN sala s ON s.id_sala = m.id_sala INNER JOIN tipo_sala tp On tp.id_tipoSala = s.id_tipoSala WHERE tp.id_tipoSala = ? AND c.id_camarero = ?";
        $stmtTipoSala = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmtTipoSala, $sqlTipoSala);
        mysqli_stmt_bind_param($stmtTipoSala, "ii", $tipoSala, $camarero);
    }
    mysqli_stmt_execute($stmtTipoSala);
    $result = mysqli_stmt_get_result($stmtTipoSala);
    if (isset($_SESSION['sala']) && $_SESSION['sala']) {
        unset($_SESSION['sala']);
    }
} else if (isset($_GET['sala'])) {
    // Recoge el valor sala por method GET
    $id_sala = mysqli_real_escape_string($conn, trim($_GET['sala']));
    $_SESSION['sala'] = htmlspecialchars($id_sala);
    $salaID = mysqli_real_escape_string($conn, trim($_SESSION['tipoSala']));
    // Consulta para filtrar por tipo de sala y sala para mostrar en la tabla
    if (!isset($_SESSION['camarero'])) {
        // Recoge todos los datos de las ocupas y desocupas de cada sala
        $sqlSala = "SELECT c.nombre, tp.tipo_sala, s.nombre_sala, m.id_mesa, h.hora_inicio, h.hora_fin, s.id_sala FROM historial h INNER JOIN camarero c ON h.id_camarero = c.id_camarero INNER JOIN mesa m ON m.id_mesa = h.id_mesa INNER JOIN sala s ON s.id_sala = m.id_sala INNER JOIN tipo_sala tp On tp.id_tipoSala = s.id_tipoSala WHERE tp.id_tipoSala = ? AND s.id_sala = ?";
        $stmtSala = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmtSala, $sqlSala);
        mysqli_stmt_bind_param($stmtSala, "ii", $salaID, $id_sala);
    } else {
        // Recoge los datos de un solo camarero elegido previamente para ver sus ocupas y desocupas
        $camarero = mysqli_real_escape_string($conn, trim($_SESSION['camarero']));
        $sqlSala = "SELECT c.nombre, tp.tipo_sala, s.nombre_sala, m.id_mesa, h.hora_inicio, h.hora_fin, s.id_sala FROM historial h INNER JOIN camarero c ON h.id_camarero = c.id_camarero INNER JOIN mesa m ON m.id_mesa = h.id_mesa INNER JOIN sala s ON s.id_sala = m.id_sala INNER JOIN tipo_sala tp On tp.id_tipoSala = s.id_tipoSala WHERE tp.id_tipoSala = ? AND s.id_sala = ? AND c.id_camarero = ?";
        $stmtSala = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmtSala, $sqlSala);
        mysqli_stmt_bind_param($stmtSala, "iii", $salaID, $id_sala, $camarero);
    }
    mysqli_stmt_execute($stmtSala);
    $result = mysqli_stmt_get_result($stmtSala);
} else if (isset($_GET['mesa'])) {
    if (!isset($_SESSION['sala'])) {
        // Recoge los datos que vienen por method GET
        $idMesa = mysqli_real_escape_string($conn, trim($_GET['mesa']));
        // Consulta para ver todas las mesas
        $sqlMesa = "SELECT c.nombre, tp.tipo_sala, s.nombre_sala, m.id_mesa, h.hora_inicio, h.hora_fin FROM historial h INNER JOIN camarero c ON h.id_camarero = c.id_camarero INNER JOIN mesa m ON m.id_mesa = h.id_mesa INNER JOIN sala s ON s.id_sala = m.id_sala INNER JOIN tipo_sala tp On tp.id_tipoSala = s.id_tipoSala WHERE m.id_mesa = ?";
        $stmtsMesa = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmtsMesa, $sqlMesa);
        mysqli_stmt_bind_param($stmtsMesa, "i", $idMesa);
        mysqli_stmt_execute($stmtsMesa);
        $result = mysqli_stmt_get_result($stmtsMesa);
    } else {
        // Recoge los datos que vienen por method GET y sesiones
        $idMesa = mysqli_real_escape_string($conn, trim($_GET['mesa']));
        $sala = mysqli_real_escape_string($conn, trim($_SESSION['sala']));
        // Consulta para ver todas las mesas
        $sqlMesaS = "SELECT c.nombre, tp.tipo_sala, s.nombre_sala, m.id_mesa, h.hora_inicio, h.hora_fin, s.id_sala FROM historial h INNER JOIN camarero c ON h.id_camarero = c.id_camarero INNER JOIN mesa m ON m.id_mesa = h.id_mesa INNER JOIN sala s ON s.id_sala = m.id_sala INNER JOIN tipo_sala tp On tp.id_tipoSala = s.id_tipoSala WHERE m.id_mesa = ? AND s.id_sala = ?";
        $stmtsMesaS = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmtsMesaS, $sqlMesaS);
        mysqli_stmt_bind_param($stmtsMesaS, "ii", $idMesa, $sala);
        mysqli_stmt_execute($stmtsMesaS);
        $result = mysqli_stmt_get_result($stmtsMesaS);
    }
} else if (isset($_GET['tiempo'])) {
    $tiempo = mysqli_real_escape_string($conn, trim($_GET['tiempo']));
    $_SESSION['tiempo'] = $tiempo;
    if(!isset($_SESSION['camarero'])){
        // Consulta para obtener los datos según el tiempo
        $sqlTiempo = "SELECT c.nombre, tp.tipo_sala, s.nombre_sala, m.id_mesa, h.hora_inicio, h.hora_fin FROM historial h INNER JOIN camarero c ON h.id_camarero = c.id_camarero INNER JOIN mesa m ON m.id_mesa = h.id_mesa INNER JOIN sala s ON s.id_sala = m.id_sala INNER JOIN tipo_sala tp On tp.id_tipoSala = s.id_tipoSala WHERE (h.hora_inicio BETWEEN DATE_SUB(?, INTERVAL 24 HOUR) AND DATE_ADD(?, INTERVAL 24 HOUR))OR (h.hora_fin BETWEEN DATE_SUB(?, INTERVAL 24 HOUR) AND DATE_ADD(?, INTERVAL 24 HOUR))";
        $stmtTiempo = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmtTiempo, $sqlTiempo);
        mysqli_stmt_bind_param($stmtTiempo, "ssss", $tiempo, $tiempo, $tiempo, $tiempo);
    } else {
        $camarero = mysqli_real_escape_string($conn, trim($_SESSION['camarero']));
        $sqlTiempo = "SELECT c.nombre, tp.tipo_sala, s.nombre_sala, m.id_mesa, h.hora_inicio, h.hora_fin FROM historial h INNER JOIN camarero c ON h.id_camarero = c.id_camarero INNER JOIN mesa m ON m.id_mesa = h.id_mesa INNER JOIN sala s ON s.id_sala = m.id_sala INNER JOIN tipo_sala tp On tp.id_tipoSala = s.id_tipoSala WHERE ((h.hora_inicio BETWEEN DATE_SUB(?, INTERVAL 24 HOUR) AND DATE_ADD(?, INTERVAL 24 HOUR))OR (h.hora_fin BETWEEN DATE_SUB(?, INTERVAL 24 HOUR) AND DATE_ADD(?, INTERVAL 24 HOUR))) AND (c.id_camarero = ?)";
        $stmtTiempo = mysqli_stmt_init($conn);
        mysqli_stmt_prepare($stmtTiempo, $sqlTiempo);
        mysqli_stmt_bind_param($stmtTiempo, "ssssi", $tiempo, $tiempo, $tiempo, $tiempo, $camarero);
    }
    mysqli_stmt_execute($stmtTiempo);
    $result = mysqli_stmt_get_result($stmtTiempo);
} else if (isset($_GET['query'])) {
    $busqueda = mysqli_real_escape_string($conn, trim($_GET['query']));
    $sqlBusqueda = "SELECT c.nombre, tp.tipo_sala, s.nombre_sala, m.id_mesa, h.hora_inicio, h.hora_fin FROM historial h INNER JOIN camarero c ON h.id_camarero = c.id_camarero INNER JOIN mesa m ON m.id_mesa = h.id_mesa INNER JOIN sala s ON s.id_sala = m.id_sala INNER JOIN tipo_sala tp On tp.id_tipoSala = s.id_tipoSala WHERE c.nombre = ? OR tp.tipo_sala = ? OR s.nombre_sala = ? OR m.id_mesa = ?";
    $stmtBusqueda = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmtBusqueda, $sqlBusqueda);
    mysqli_stmt_bind_param($stmtBusqueda, "sssi", $busqueda, $busqueda, $busqueda, $busqueda);
    mysqli_stmt_execute($stmtBusqueda);
    $result = mysqli_stmt_get_result($stmtBusqueda);
    $_SESSION['busqueda'] = $busqueda;
} else {
    // Consulta para obtener todos los datos de la base de datos
    $sql = "SELECT c.nombre, tp.tipo_sala, s.nombre_sala, m.id_mesa, h.hora_inicio, h.hora_fin FROM historial h INNER JOIN camarero c ON h.id_camarero = c.id_camarero INNER JOIN mesa m ON m.id_mesa = h.id_mesa INNER JOIN sala s ON s.id_sala = m.id_sala INNER JOIN tipo_sala tp On tp.id_tipoSala = s.id_tipoSala";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $sql);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
}
