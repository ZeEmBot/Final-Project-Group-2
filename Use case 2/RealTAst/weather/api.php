<?php
header("Content-Type: application/json");

include __DIR__ . '/../geolocation/db.php';

// Pastikan koneksi database berhasil
if (!$conn) {
    echo json_encode(["status" => "error", "message" => "Koneksi ke database gagal."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Jika parameter city_name diberikan
    if (isset($_GET['city_name']) && !empty($_GET['city_name'])) {
        $cityName = $conn->real_escape_string($_GET['city_name']);

        // Query ke database berdasarkan city_name
        $sql = "SELECT city_name, humidity, air_quality_index AS aqi FROM weather WHERE city_name = '$cityName'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();

            echo json_encode([
                "status" => "success",
                "data" => $data
            ]);
        } else {
            // Data tidak ditemukan
            echo json_encode([
                "status" => "error",
                "message" => "Data untuk kota '" . htmlspecialchars($cityName) . "' tidak ditemukan."
            ]);
        }
    }
    // Jika parameter temperature diberikan
    elseif (isset($_GET['temperature']) && !empty($_GET['temperature'])) {
        $temperature = $conn->real_escape_string($_GET['temperature']);

        // Query berdasarkan temperatur
        $sql = "SELECT city_name, temperature FROM weather WHERE temperature = '$temperature'";
        $result = $conn->query($sql);

        if ($result) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }

            if (!empty($data)) {
                echo json_encode([
                    "status" => "success",
                    "data" => $data
                ]);
            } else {
                echo json_encode([
                    "status" => "error",
                    "message" => "Tidak ada kota dengan suhu " . htmlspecialchars($temperature) . "°C."
                ]);
            }
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Terjadi kesalahan dalam mengakses data."
            ]);
        }
    } else {
        // Jika tidak ada parameter valid
        echo json_encode([
            "status" => "error",
            "message" => "Parameter 'city_name' atau 'temperature' diperlukan dan tidak boleh kosong."
        ]);
    }
} else {
    // Metode selain GET tidak diizinkan
    echo json_encode([
        "status" => "error",
        "message" => "Hanya metode GET yang diperbolehkan."
    ]);
}

$conn->close();
?>
