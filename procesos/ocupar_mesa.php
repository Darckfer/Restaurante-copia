<?php
session_start();
include_once './conexion.php';

if (isset($_SESSION['id_camarero'])) {
    $id_tipoSala = mysqli_real_escape_string($conn, trim($_POST['id_tipoSala']));
    $idCamarero = mysqli_real_escape_string($conn, trim($_SESSION['id_camarero']));
    $idSala = mysqli_real_escape_string($conn, trim($_POST['id_sala']));
    $idMesa = mysqli_real_escape_string($conn, trim($_POST['id_mesa']));
    $num_sillas = mysqli_real_escape_string($conn, trim($_POST['num_sillas']));
    $num_sillas_real = mysqli_real_escape_string($conn, trim($_POST['num_sillas_real']));
    if ($_POST['libre'] == 0) {
        mysqli_autocommit($conn, false);

        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);
        try {
            $sqlRestaStock = "SELECT * FROM stock";
            $resultRestaStock = mysqli_query($conn, $sqlRestaStock);
            while ($row = mysqli_fetch_array($resultRestaStock)) {
                $VerificaStock = $row['sillas_stock'];
            }

            if ($VerificaStock >= ($num_sillas - 2)) {

                if ($num_sillas != $num_sillas_real) {
                    if ($num_sillas > $num_sillas_real) {
                        // Si se aumenta el número de sillas, resta el stock
                        $nuevoStockSillas = $VerificaStock - ($num_sillas - $num_sillas_real);
                    } else {
                        // Si se reduce el número de sillas, suma el stock
                        $nuevoStockSillas = $VerificaStock + ($num_sillas_real - $num_sillas);
                    }

                    $sqlLimitSillas = "UPDATE stock SET sillas_stock = ?";
                    $stmtLimitSillas = mysqli_stmt_init($conn);
                    mysqli_stmt_prepare($stmtLimitSillas, $sqlLimitSillas);
                    mysqli_stmt_bind_param($stmtLimitSillas, 'i', $nuevoStockSillas);
                    mysqli_stmt_execute($stmtLimitSillas);
                    mysqli_stmt_close($stmtLimitSillas);
                }

                $sql = "UPDATE mesa SET libre = ?, num_sillas = ? WHERE id_mesa = ?";
                $stmt = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmt, $sql);
                $reservado = 1;
                mysqli_stmt_bind_param($stmt, 'ssi', $reservado, $num_sillas, $idMesa);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);

                $sqlOcupat = "INSERT INTO historial (id_camarero, id_mesa, hora_inicio) VALUES (?, ?, NOW())";
                $stmtOcupat = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmtOcupat, $sqlOcupat);
                mysqli_stmt_bind_param($stmtOcupat, 'ii', $idCamarero, $idMesa);
                mysqli_stmt_execute($stmtOcupat);
                mysqli_stmt_close($stmtOcupat);

                mysqli_commit($conn);
                mysqli_close($conn);
                $_SESSION['errorStock'] = false;
                $_SESSION['successOcupat'] = true;
            } else {
                $_SESSION['errorStock'] = true;
?>
                <form action="../ocupar/mesa.php" method="POST" name="formulario">
                    <input type="hidden" name="id_tipoSala" value="<?php echo $id_tipoSala ?>">
                    <input type="hidden" name="id_sala" value="<?php echo $idSala ?>">
                </form>
                <script language="JavaScript">
                    document.formulario.submit();
                </script>
            <?php
            }
            ?>
            <form action="../ocupar/mesa.php" method="POST" name="formulario">
                <input type="hidden" name="id_tipoSala" value="<?php echo $id_tipoSala ?>">
                <input type="hidden" name="id_sala" value="<?php echo $idSala ?>">
            </form>
            <script language="JavaScript">
                document.formulario.submit();
            </script>
        <?php

        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "Error: " . $e->getMessage();
            mysqli_close($conn);
            exit();
        }
    } else {
        mysqli_autocommit($conn, false);

        mysqli_begin_transaction($conn, MYSQLI_TRANS_START_READ_WRITE);

        try {
            $sqlRestaStock = "SELECT * FROM stock";
            $resultRestaStock = mysqli_query($conn, $sqlRestaStock);
            $VerificaStock = 0;
            while ($row = mysqli_fetch_array($resultRestaStock)) {
                $VerificaStock = $row['sillas_stock'];
            }

            if ($num_sillas != $num_sillas_real) {
                $nuevoStockSillas = $num_sillas_real - $num_sillas + $VerificaStock;
                $sqlLimitSillas = "UPDATE stock SET sillas_stock = ?";
                $stmtLimitSillas = mysqli_stmt_init($conn);
                mysqli_stmt_prepare($stmtLimitSillas, $sqlLimitSillas);
                mysqli_stmt_bind_param($stmtLimitSillas, 'i', $nuevoStockSillas);
                mysqli_stmt_execute($stmtLimitSillas);
                mysqli_stmt_close($stmtLimitSillas);
            }

            $sql = "UPDATE mesa SET libre = ?, num_sillas = ? WHERE id_mesa = ?";
            $stmt = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmt, $sql);
            $reservado = 0;
            mysqli_stmt_bind_param($stmt, 'ssi', $reservado, $num_sillas, $idMesa);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            $null = '0000-00-00 00:00:00';
            $sqlIDH = "SELECT * FROM historial WHERE id_mesa = ? AND hora_fin = ?";
            $stmtIDH = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmtIDH, $sqlIDH);
            mysqli_stmt_bind_param($stmtIDH, 'is', $idMesa, $null);
            mysqli_stmt_execute($stmtIDH);
            $resultIDH = mysqli_stmt_get_result($stmtIDH);
            if (mysqli_num_rows($resultIDH) > 0) {
                $fila = mysqli_fetch_assoc($resultIDH);
                $idH = $fila['id_historial'];
            }

            $sqlDesocupat = "UPDATE historial SET hora_fin = NOW() WHERE id_mesa = ? AND id_historial = ?";
            $stmtDesocupat = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmtDesocupat, $sqlDesocupat);
            mysqli_stmt_bind_param($stmtDesocupat, 'ii', $idMesa, $idH);
            mysqli_stmt_execute($stmtDesocupat);
            mysqli_stmt_close($stmtDesocupat);

            mysqli_commit($conn);

            mysqli_close($conn);

            $_SESSION['successDesocupat'] = true;

        ?>
            <form action="../ocupar/mesa.php" method="POST" name="formulario">
                <input type="hidden" name="id_tipoSala" value="<?php echo $id_tipoSala ?>">
                <input type="hidden" name="id_sala" value="<?php echo $idSala ?>">
            </form>
            <script language="JavaScript">
                document.formulario.submit();
            </script>
<?php
        } catch (Exception $e) {
            mysqli_rollback($conn);
            echo "Error: " . $e->getMessage();
            mysqli_close($conn);
            exit();
        }
    }
} else {
    header('Location: ../index.php');
    exit();
}
?>