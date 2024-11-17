<!-- header.php -->
<header>
  <nav>
    <div class="sesionIniciada">
      <p>Usuario: <?php echo $_SESSION['nombre'] ?></p>
    </div>
    <div class="cerrarSesion">
      <a id="bug" href="./reservas.php">
        <button type="submit" class="btn btn-light" id="cerrarSesion">reservas</button>
      </a>
      <a id="bug" href="../ocupar/filtros.php">
        <button type="submit" class="btn btn-light" id="cerrarSesion">Filtrar</button>
      </a>
      <a href="../procesos/logout.php">
        <button type="submit" class="btn btn-dark" id="cerrarSesion">Cerrar Sesión</button>
      </a>
    </div>
  </nav>
</header>