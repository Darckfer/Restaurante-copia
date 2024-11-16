<?php
session_start();
include_once './procesos/conexion.php';
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

    if (isset($_SESSION['reservada']) && $_SESSION['reservada']) {
        unset($_SESSION['reservada']);
        echo "<script>let reservado = true;</script>";
    }
    if (isset($_SESSION['noreservada']) && $_SESSION['noreservada']) {
        unset($_SESSION['noreservada']);
        echo "<script>let no_reservado = true;</script>";
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
        <header>
            <nav>
                <div class="sesionIniciada">
                    <p>Usuario: <?php echo $_SESSION['nombre'] ?></p>
                </div>
                <div class="cerrarSesion">
                    <a id="bug" href="./reservas.php">
                        <button type="submit" class="btn btn-light" id="cerrarSesion">reservas</button>
                    </a>
                    <a id="bug" href="./filtros.php">
                        <button type="submit" class="btn btn-light" id="cerrarSesion">Filtrar</button>
                    </a>
                    <a href="./procesos/logout.php">
                        <button type="submit" class="btn btn-dark" id="cerrarSesion">Cerrar Sesión</button>
                    </a>
                </div>
            </nav>
        </header>
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
                    <form class="formImgComedor" action="./procesos/ocupar_mesa.php" method="POST">
                        <input type="hidden" name="libre" value="<?php echo $fila['libre'] ?>">
                        <input type="hidden" name="id_tipoSala" value="<?php echo $id ?>">
                        <input type="hidden" name="id_mesa" value="<?php echo $fila['id_mesa'] ?>">
                        <input type="hidden" name="id_sala" value="<?php echo $fila['id_sala'] ?>">
                        <input type="hidden" name="num_sillas_real" value="<?php echo $fila['num_sillas'] ?>">
                        <input type="hidden" name="num_sillas" value="<?php echo $fila['num_sillas'] ?>">
                        <button class="botonImg" type="button" onclick='reservar(this.form)'><img class="imagen" src="../img/<?php
                                                                                                                                if ($fila['num_sillas'] == 1 || $fila['num_sillas'] == 2) {
                                                                                                                                    echo $fila['libre'] .  2;
                                                                                                                                } elseif ($fila['num_sillas'] == 3 || $fila['num_sillas'] == 4) {
                                                                                                                                    echo $fila['libre'] . 4;
                                                                                                                                } elseif ($fila['num_sillas'] == 5 || $fila['num_sillas'] == 6) {
                                                                                                                                    echo $fila['libre'] . 6;
                                                                                                                                } elseif ($fila['num_sillas'] == 7 || $fila['num_sillas'] == 8) {
                                                                                                                                    echo $fila['libre'] . 8;
                                                                                                                                } elseif ($fila['num_sillas'] == 9 || $fila['num_sillas'] == 10) {
                                                                                                                                    echo $fila['libre'] . 10;
                                                                                                                                }
                                                                                                                                ?>.png" alt=""></button>
                    </form>
                <?php
                    echo "</div>";
                    echo "<label class='labelTipo'> Nº Sillas: " . $fila['num_sillas'] . "</label>";
                    echo "</div>";
                }

                ?>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.14.5/dist/sweetalert2.all.min.js" integrity="sha256-1m4qVbsdcSU19tulVTbeQReg0BjZiW6yGffnlr/NJu4=" crossorigin="anonymous"></script>
        <script>
            function reservar(form) {
                Swal.fire({
                    title: "Reservar la mesa para: " + form.num_sillas.value + " ocupantes?",
                    text: "cambia el número de sillas aqui: ",
                    icon: "warning",
                    input: "text",
                    inputValue: form.num_sillas.value,
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Confirmar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (result.value <= 10 && result.value >= 2) {
                            form.num_sillas.value = result.value;
                            form.submit();
                        } else {
                            Swal.fire({
                                title: "Limite es 10!",
                                text: "No puedes pedir mas de 10 sillas",
                                icon: "warning",
                                confirmButtonText: "Aceptar"
                            });
                        }
                    }
                });
            }
            if (typeof reservado !== "undefined" && reservado) {
                Swal.fire({
                    title: "Reserva creada!",
                    text: "se ha reservado correctamente",
                    icon: "success",
                    confirmButtonText: "Aceptar"
                });
            }
            if (typeof no_reservado !== "undefined" && no_reservado) {
                Swal.fire({
                    title: "Reserva no creada!",
                    text: "ya existe una reserva con esa fecha o esta ocupada actualmete",
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            }
        </script>
        <footer></footer>
    </body>

    </html>

<?php
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}

?>