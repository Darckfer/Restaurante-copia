<?php
session_start();
if (!isset($_SESSION['id_camarero'])) {
  header('Location: ../index.php');
  exit();
}
require_once '../procesos/conexion.php';
require_once "../procesos/filtros.php";
$filtrarSalas = isset($_SESSION['tipoSala']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Salas y Mesas</title>
  <link rel="stylesheet" href="../css/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script>
    function cambiarTabla() {
      if (window.matchMedia("(max-width: 900px)").matches) {
        document.getElementById("nombreSala").textContent = 'Sala';
        document.getElementById("numeroMesa").textContent = 'Nº';
        document.getElementById("horaIni").textContent = 'Hora ini.';
      } else {
        document.getElementById("nombreSala").textContent = 'Nombre de sala';
        document.getElementById("numeroMesa").textContent = 'Número de mesa';
        document.getElementById("horaIni").textContent = 'Hora inicio';
      }
    }
    window.onload = cambiarTabla;
    window.onresize = cambiarTabla;
  </script>
</head>

<body class="body2">
  <?php

  include '../header.php';

  ?>
  <div id="divReiniciar">
  <a href="../procesos/borrarSesiones.php?salir=1">
    <button class="btn btn-danger">Volver</button>
  </a>
  <a href="../procesos/borrarSesiones.php?borrar=5">
    <button class="btn btn-warning">Reiniciar Filtros</button>
  </a>
  </div>

  <nav class="navbar navbar-expand-lg barra">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle enlace-barra" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Orden de tiempo</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="filtros.php?orden=asc">Más antiguo</a></li>
              <li><a class="dropdown-item" href="filtros.php?orden=desc">Último</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Camarero</a>
            <ul class="dropdown-menu">
              <?php
              $sqlCamarero = "SELECT * FROM camarero";
              $resultCamarero = mysqli_query($conn, $sqlCamarero);
              while ($fila = mysqli_fetch_array($resultCamarero)) {
                $idCamarero = htmlspecialchars($fila['id_camarero']);
                $nomCamarero = htmlspecialchars($fila['nombre']);
                echo "<li><a class='dropdown-item enlace-barra' href='filtros.php?camarero={$idCamarero}'>$nomCamarero</a></li>";
              }
              ?>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Tipo de sala</a>
            <ul class="dropdown-menu">
              <?php
              $sqlNumTipoSala = 'SELECT * FROM tipo_Sala';
              $resultNumTipoSala = mysqli_query($conn, $sqlNumTipoSala);
              while ($fila = mysqli_fetch_array($resultNumTipoSala)) {
                $idTipoSala = htmlspecialchars($fila['id_tipoSala']);
                $nombreTipoSala = htmlspecialchars($fila['tipo_sala']);
                echo "<li><a class='dropdown-item enlace-barra' href='filtros.php?tipoSala={$idTipoSala}'>$nombreTipoSala</a>";
              }
              ?>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" <?php echo !isset($_SESSION['tipoSala']) ? 'disabled' : ''; ?> href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Salas</a>
            <ul class="dropdown-menu">
          </li>
          <?php
          // Comprueba si se ha seleccionado un tipo de sala en la sesión
          if (isset($_SESSION['tipoSala'])) {
            $tipoSala = mysqli_real_escape_string($conn, trim($_SESSION['tipoSala']));
            $sqlSalas = "SELECT * FROM sala WHERE id_tipoSala = ?";
            $stmtSalas = mysqli_stmt_init($conn);
            mysqli_stmt_prepare($stmtSalas, $sqlSalas);
            mysqli_stmt_bind_param($stmtSalas, "i", $tipoSala);
            mysqli_stmt_execute($stmtSalas);
            $resultSalas = mysqli_stmt_get_result($stmtSalas);
            while ($fila = mysqli_fetch_array($resultSalas)) {
              $idSala = htmlspecialchars($fila['id_sala']);
              $nombreSala = htmlspecialchars($fila['nombre_sala']);
              echo "<li><a class='dropdown-item enlace-barra' href='filtros.php?sala={$idSala}'>$nombreSala</a></li>";
            }
          } else {
            echo "<li class='dropdown-item disabled'>Seleccione un tipo de sala primero</li>";
          }
          ?>
        </ul>
        </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle enlace-barra" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Numero Mesa</a>
            <ul class="dropdown-menu scrollable-dropdown">
              <?php
                if(isset($_SESSION['sala'])){
                  $sala = mysqli_real_escape_string($conn, trim($_SESSION['sala']));
                  $sqlMesaS ="SELECT m.id_mesa FROM mesa m INNER JOIN sala s ON m.id_sala = s.id_sala WHERE s.id_sala = ?";
                  $stmtMesaS = mysqli_stmt_init($conn);
                  mysqli_stmt_prepare($stmtMesaS, $sqlMesaS);
                  mysqli_stmt_bind_param($stmtMesaS, "i", $sala);
                  mysqli_stmt_execute($stmtMesaS);
                  $resultMesaS = mysqli_stmt_get_result($stmtMesaS);
                  while($fila = mysqli_fetch_array($resultMesaS)){
                    $idMesa = htmlspecialchars($fila['id_mesa']);
                    echo "<li><a class='dropdown-item' href='filtros.php?mesa=$idMesa'>$idMesa</a></li>";
                  }
                } else {
                  $sqlMesas = "SELECT * FROM mesa";
                  $resultMesas = mysqli_query($conn, $sqlMesas);
                  while($fila = mysqli_fetch_array($resultMesas)){
                    $idMesa = htmlspecialchars($fila['id_mesa']);
                    echo "<li><a class='dropdown-item' href='filtros.php?mesa=$idMesa'>$idMesa</a></li>";
                  }
                }
              ?>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle enlace-barra" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Tiempo</a>
            <ul id="bajar" class="dropdown-menu">
              <li class="centrar">Introduce una fecha</li>
              <li><form id="formFecha" method="GET" action="">
                <input id="inputFecha" type="datetime-local" name="tiempo" id="tiempo">
                <button id="btnFecha" class="btn btn-outline-success" type="submit">Buscar</button>
              </form></li>
            </ul>
        </li>
        </ul>
        <form class="d-flex" role="search" method="get" action="">
          <input class="form-control me-2" type="search" name="query" placeholder="Buscar" aria-label="Search">
          <button class="btn btn-outline-success" type="submit">Buscar</button>
        </form>
        <div id="resultados">
        </div>
      </div>
    </div>
  </nav>
  <?php
  echo "<h1 id=historial>Historial de mesas</h1>";
  if (mysqli_num_rows($result) > 0) {
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>Nombre</th>";
    echo "<th class='ocultarSala'>Sala</th>";
    echo "<th id='nombreSala'>Nombre de sala</th>";
    echo "<th id='numeroMesa'>Número de mesa</th>";
    echo "<th id='horaIni'>Hora inicio</th>";
    echo "<th>Hora fin</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    while ($fila = mysqli_fetch_assoc($result)) {

      echo "<tr>";
      echo "<td>" . $fila['nombre'] . "</td>";
      echo "<td class='ocultarSala'>" . $fila['tipo_sala'] . "</td>";
      echo "<td>" . $fila['nombre_sala'] . "</td>";
      echo "<td>" . $fila['id_mesa'] . "</td>";
      echo "<td>" . $fila['hora_inicio'] . "</td>";
      echo "<td>" . ($fila['hora_fin'] == '0000-00-00 00:00:00' ? "Pendiente" : $fila['hora_fin']) . "</td>";
      echo "</tr>";
    }
  } else {
    ?>
    <p id="noResultado">No hay resultados
    <?php
  }
  echo "</tbody>";
  echo "</table>";
  ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="../validations/js/filtrosMesas.js"></script>
  <footer></footer>
</body>

</html>