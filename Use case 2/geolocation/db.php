<?php
    $servername = "localhost";
    $username = "root";
    $password = "rahasia";
    $dbname = "ta_integrasi_tst";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
?>