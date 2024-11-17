<?php
session_start();
include_once '../procesos/conexion.php';
include './comprobar_reserva.php';
if (!isset($_SESSION['id_camarero'])) {
    header('location:../index.php');
    exit();
}
if (!isset($_POST['id_tipoSala'])) {
    header('Location: ./index.php');
    exit();
} else {
    $id = mysqli_real_escape_string($conn, trim($_POST['id_tipoSala']));
    $id_sala = mysqli_real_escape_string($conn, trim($_POST['id_sala']));
    $query = "SELECT * FROM mesa WHERE id_sala = ?";
    $stmt = mysqli_stmt_init($conn);
    mysqli_stmt_prepare($stmt, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_sala);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);
    if (isset($_SESSION['successOcupat']) && $_SESSION['successOcupat']) {
        unset($_SESSION['successOcupat']);
        echo "<script>let ocupat = true;</script>";
    }
    if (isset($_SESSION['successDesocupat']) && $_SESSION['successDesocupat']) {
        unset($_SESSION['successDesocupat']);
        echo "<script>let desocupat = true;</script>";
    }
    if (isset($_SESSION['errorStock'])) {
        echo "<script>let errorStock = true;</script>";
        unset($_SESSION['errorStock']);
    }
    if (isset($_SESSION['errorReservaFutura']) && $_SESSION['errorReservaFutura']) {
        unset($_SESSION['errorReservaFutura']);
        echo "<script>let errorReservaFutura = true;</script>";
    }
    $numero = mysqli_num_rows($result);
    $nuevoNumero = 4;
    switch ($numero) {
        case 4:
            $nuevoNumero = 5;
            break;
        case 5:
            $nuevoNumero = 4;
            break;
        case 6:
            $nuevoNumero = 4;
            break;
    }
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/imagenes.css">
        <link rel="stylesheet" href="../css/style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <title>Selecciona una mesa</title>
    </head>

    <body class="body2">
        <?php
        include '../header.php';
        ?>
        <div id="divMesa">
            <div>
                <form class="formImgComedor" action="./tipoSala.php" method="post">
                    <input type="hidden" name="id_tipoSala" value="<?php echo $_POST['id_tipoSala']; ?>">
                    <button type="submit" class="btn btn-danger">Volver</button>
                </form>
            </div>
            <!-- <a href="../view/index.php">
                <button class="btn btn-danger">Volver</button>
            </a> -->
            <h1 id="centrar">Selecciona una ubicación!</h1>
            <p></p>
        </div>

        <div class="container">
            <div class="row">

                <?php
                foreach ($result as $fila) {
                    echo "<div class='col-md-$nuevoNumero mb-4'>";
                    echo "<div class='container_img'>";
                ?>
                    <form class="formImgComedor" action="../procesos/ocupar_mesa.php" method="POST">
                        <input type="hidden" name="libre" value="<?php echo $fila['id_estado'] ?>">
                        <input type="hidden" name="id_tipoSala" value="<?php echo $id ?>">
                        <input type="hidden" name="id_mesa" value="<?php echo $fila['id_mesa'] ?>">
                        <input type="hidden" name="id_sala" value="<?php echo $fila['id_sala'] ?>">
                        <input type="hidden" name="num_sillas_real" value="<?php echo $fila['num_sillas'] ?>">
                        <input type="hidden" name="num_sillas" value="<?php echo $fila['num_sillas'] ?>">
                        <button class="botonImg" type="button"
                            <?php if ($fila['id_estado'] == 2 || $fila['id_estado'] == 3) {
                                echo "onclick='desocupar(this.form)'";
                            } else {
                                echo "onclick='confirmAction(this.form)'";
                            }
                            ?>><img class="imagen" src="../img/<?php
                                                                if ($fila['num_sillas'] == 1 || $fila['num_sillas'] == 2) {
                                                                    echo $fila['id_estado'] .  2;
                                                                } elseif ($fila['num_sillas'] == 3 || $fila['num_sillas'] == 4) {
                                                                    echo $fila['id_estado'] . 4;
                                                                } elseif ($fila['num_sillas'] == 5 || $fila['num_sillas'] == 6) {
                                                                    echo $fila['id_estado'] . 6;
                                                                } elseif ($fila['num_sillas'] == 7 || $fila['num_sillas'] == 8) {
                                                                    echo $fila['id_estado'] . 8;
                                                                } elseif ($fila['num_sillas'] == 9 || $fila['num_sillas'] == 10) {
                                                                    echo $fila['id_estado'] . 10;
                                                                }
                                                                ?>.png" alt="RESERVADA"></button>
                    </form>
                <?php
                    echo "</div>";
                    echo "<label class='labelTipo'>Nº mesa: {$fila['id_mesa']}, Nº Sillas: {$fila['num_sillas']}</label>";
                    echo "</div>";
                }

                ?>
            </div>
        </div>
    </body>

    </html>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js" integrity="sha256-1m4qVbsdcSU19tulVTbeQReg0BjZiW6yGffnlr/NJu4=" crossorigin="anonymous"></script>
    <script>
        function confirmAction(form) {
            Swal.fire({
                title: "Estás seguro de ocupar la mesa: " + form.id_mesa.value + " para " + form.num_sillas.value + "?",
                text: "Puedes cambiar el número de sillas aquí:",
                icon: "warning",
                input: "text",
                inputValue: form.num_sillas.value,
                inputValidator: (value) => {
                    if (value < 1 || value > 10) {
                        return "El número de sillas debe ser un número entre 2 y 10.";
                    }
                },

                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmar",
            }).then((result) => {
                if (result.isConfirmed) {
                    form.num_sillas.value = result.value;
                    form.submit();
                }
            });
        }

        function desocupar(form) {
            Swal.fire({
                title: "Estás seguro de desocupar la mesa para " + form.num_sillas.value + "?",
                text: "cambia el número de sillas aqui: ",
                icon: "warning",
                input: "text",
                inputValue: (form.num_sillas.value < 2 || form.num_sillas.value > 4) ? 2 : form.num_sillas.value,
                inputValidator: (value) => {
                    if (value < 2 || value > 10) {
                        return "El número de sillas debe estar entre 2 y 10.";
                    }
                },

                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmar"
            }).then((result) => {
                if (result.isConfirmed) {
                    form.num_sillas.value = result.value;
                    form.submit();
                }
            });
        }
        if (typeof errorReservaFutura !== 'undefined' && errorReservaFutura) {
            Swal.fire({
                title: "Mesa Reservada",
                text: "<?php echo isset($_SESSION['mensajeReserva']) ? $_SESSION['mensajeReserva'] : ""; ?>",
                icon: "warning",
                confirmButtonText: "Aceptar"
            });
        }
        if (typeof errorStock !== 'undefined' && errorStock) {
            Swal.fire({
                title: "No disponemos de sillas!",
                text: "En este momento no contamos con tantas sillas",
                icon: "error",
                confirmButtonText: "Aceptar"
            });
        }
        if (typeof ocupat !== "undefined" && ocupat) {
            Swal.fire({
                title: "Mesa Ocupada!",
                text: "La mesa ha sido ocupada exitosamente!",
                icon: "success",
                confirmButtonText: "Aceptar"
            });
        }
        if (typeof desocupat !== "undefined" && desocupat) {
            Swal.fire({
                title: "Mesa Desocupada!",
                text: "La mesa ha sido desocupada exitosamente!",
                icon: "success",
                confirmButtonText: "Aceptar"
            });
        }
    </script>
<?php
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

?>