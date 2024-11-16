<?php
$servidor = "localhost";
$usuario = "root";
$pwd = "";
$db = "db_restaurante";

try {
    $conn = mysqli_connect($servidor, $usuario, $pwd, $db);
} catch (Exception $e) {
    echo $e->getMessage();
}
