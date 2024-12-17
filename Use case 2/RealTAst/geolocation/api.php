<?php
header("Content-Type: application/json");

// Include file koneksi database
include __DIR__ . '/db.php';

// Periksa koneksi database
if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Koneksi ke database gagal."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['city_name']) && !empty($_GET['city_name'])) {
        // Ambil parameter nama kota
        $cityName = $conn->real_escape_string($_GET['city_name']);

        // Query ke database untuk mendapatkan data latitude dan longitude
        $sql = "SELECT latitude, longitude FROM geolocation WHERE city_name = '$cityName'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();
            echo json_encode(["status" => "success", "data" => $data]);
        } else {
            echo json_encode(["status" => "error", "message" => "Data lokasi untuk kota '$cityName' tidak ditemukan."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Parameter 'city_name' diperlukan dan tidak boleh kosong."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Hanya metode GET yang diperbolehkan."]);
}

$conn->close();
?>
