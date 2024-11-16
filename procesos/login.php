<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    include "./conexion.php";
    $user = mysqli_real_escape_string($conn, trim($_POST['user']));
    $pwd = mysqli_real_escape_string($conn, trim($_POST['pwd']));

    try {
        $sql = "SELECT * FROM camarero WHERE usuario=?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $user);
            mysqli_stmt_execute($stmt);
            $resultado = mysqli_stmt_get_result($stmt);
            $num_rows = mysqli_num_rows($resultado);
            if ($num_rows > 0) {
                $fila = mysqli_fetch_assoc($resultado);
                if (password_verify($pwd, $fila['password'])) {
                    session_start();
                    $_SESSION['id_camarero'] = $fila['id_camarero'];
                    $_SESSION['nombre'] = $fila['nombre'];
                    $_SESSION['usuario'] = $fila['usuario'];
                    $_SESSION['rol'] = $fila['rol'];
                    $_SESSION['success'] = true;
                    header('location: ../preinicio.php');
                    die();
                } else {
                    header('Location:../index.php?error=5');
                    die();
                }
            } else {
                header("Location:../index.php?error=5");
                die();
            }
        }
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        mysqli_close($conn);
        die();
    }
    header('location: ../index.php');
    die();
} else {
    header("Location: index.html");
    die();
}
