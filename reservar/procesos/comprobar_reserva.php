<?php
try {
    // Consulta SQL para actualizar el estado de las mesas
    $sqlUpdateMesas = "UPDATE mesa m JOIN reserva r ON m.id_mesa = r.id_mesa SET m.id_estado = CASE 
                WHEN r.fecha_inicio BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 1 HOUR) THEN 3  
                WHEN r.fecha_inicio <= NOW() AND r.fecha_fin >= NOW() THEN 3 
                ELSE m.id_estado  
            END
        WHERE r.fecha_fin >= NOW();";

    // Ejecutar la consulta
    if (mysqli_query($conn, $sqlUpdateMesas)) {
        $affectedRows = mysqli_affected_rows($conn);
    } else {
        echo "Error al actualizar las mesas: " . mysqli_error($conn);
    }
} catch (mysqli_sql_exception $e) {
    // Captura cualquier excepción generada por la consulta y muestra el error
    echo "Error al actualizar las mesas: " . $e->getMessage();
}
