<?php
    $servername = "localhost";
    $username = "root";
    $password = "senai";
    $database = "flordabia";

    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("Falha na Conexão: " . mysqli_connect_error());
    }
?>