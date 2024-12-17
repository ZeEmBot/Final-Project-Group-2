<?php
include 'db.php';

$usecase = $_GET['usecase'] ?? 'city';
$cityName = $_GET['city_name'] ?? '';
$temperature = $_GET['temperature'] ?? '';

$data = [];
$error = '';
$showDetails = false;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['usecase'])) {
    if ($usecase === 'city' && !empty($cityName)) {
        $cityNameEscaped = $conn->real_escape_string($cityName);
        $query = "SELECT latitude, longitude FROM geolocation WHERE city_name = '$cityNameEscaped'";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();
        } else {
            $error = "Data geografis untuk kota '$cityName' tidak ditemukan.";
        }
    } elseif ($usecase === 'temperature' && !empty($temperature)) {
        $temperatureEscaped = $conn->real_escape_string($temperature);
        $query = "SELECT city_name, temperature FROM weather WHERE temperature = '$temperatureEscaped'";
        $result = $conn->query($query);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            $showDetails = isset($_GET['show_details']); // Cek apakah user ingin melihat detail
        } else {
            $error = "Tidak ada kota dengan suhu $temperature°C.";
        }
    } else {
        $error = "Silakan masukkan input yang valid untuk fungsi yang dipilih.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Geolocation Website</title>
    <link rel="stylesheet" href="style.css">
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const usecaseSelect = document.getElementById('usecase');
            const cityInputDiv = document.getElementById('city-input');
            const temperatureInputDiv = document.getElementById('temperature-input');

            usecaseSelect.addEventListener('change', () => {
                if (usecaseSelect.value === 'city') {
                    cityInputDiv.style.display = 'block';
                    temperatureInputDiv.style.display = 'none';
                } else if (usecaseSelect.value === 'temperature') {
                    cityInputDiv.style.display = 'none';
                    temperatureInputDiv.style.display = 'block';
                }
            });

            const detailButton = document.getElementById('show-details');
            if (detailButton) {
                detailButton.addEventListener('click', () => {
                    window.location.href = window.location.href + '&show_details=true';
                });
            }
        });
    </script>
</head>

<body>
    <div class="container">
        <h1>Geolocation Website</h1>
        <p>Website ini memberikan informasi geografis berdasarkan pilihan Anda :</p>
        <ul>
            <li><b>Fungsi Utama :</b> Cari lokasi geografis berdasarkan nama kota.</li>
            <li><b>Usecase 3 :</b> Cari data kota yang memiliki temperatur suhu yang sama.</li>
        </ul>

        <form method="GET" action="">
            <label for="usecase">Pilih Fungsi :</label>
            <select id="usecase" name="usecase">
                <option value="city" <?= $usecase === 'city' ? 'selected' : '' ?>>Lihat Geografis Kota</option>
                <option value="temperature" <?= $usecase === 'temperature' ? 'selected' : '' ?>>Cari Temperatur suhu yang sama</option>
            </select>

            <div id="city-input" style="display: <?= $usecase === 'city' ? 'block' : 'none' ?>;">
                <label for="city_name">Nama Kota:</label>
                <input type="text" id="city_name" name="city_name" placeholder="Contoh: Jakarta" value="<?= htmlspecialchars($cityName) ?>">
            </div>

            <div id="temperature-input" style="display: <?= $usecase === 'temperature' ? 'block' : 'none' ?>;">
                <label for="temperature">Temperatur:</label>
                <input type="number" id="temperature" name="temperature" placeholder="Contoh: 30" value="<?= htmlspecialchars($temperature) ?>">
            </div>

            <button type="submit">Cari</button>
        </form>

        <div id="output">
            <?php if ($usecase === 'temperature' && isset($_GET['temperature'])) {
                    $temperature = $conn->real_escape_string($_GET['temperature']);
                    $query = "SELECT city_name, temperature FROM weather WHERE temperature = '$temperature'";
                    $result = $conn->query($query);

                    $data = [];
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $data[] = $row;
                        }
                    }

                    if (!empty($data)) {
                        echo '<h2>Hasil Pencarian:</h2>';
                        echo '<table>';
                        echo '<thead><tr><th>Nama Kota</th><th>Temperatur</th></tr></thead>';
                        echo '<tbody>';
                        foreach ($data as $city) {
                            echo '<tr>';
                            echo '<td>' . htmlspecialchars($city['city_name']) . '</td>';
                            echo '<td>' . htmlspecialchars($city['temperature']) . ' °C</td>';
                            echo '</tr>';
                        }
                        echo '</tbody>';
                        echo '</table>';

                        // Tampilkan tombol untuk detail tambahan
                        echo '<p>Apakah ingin melihat detail lainnya juga?</p>';
                        echo '<button onclick="showDetails()">Lihat Detail</button>';

                        // Div untuk menampilkan detail
                        echo '<div id="details" style="display: none;"></div>';

                        // Tambahkan skrip JavaScript untuk memuat detail tambahan
                        echo '<script>
                            function showDetails() {
                                const detailsDiv = document.getElementById("details");
                                detailsDiv.innerHTML = "<p>Loading...</p>";

                                const cityNames = ' . json_encode(array_column($data, 'city_name')) . ';

                                cityNames.forEach(city => {
                                    fetch(`http://localhost/RealTAtst/weather/api.php?city_name=${encodeURIComponent(city)}`)
                                        .then(response => {
                                            if (!response.ok) throw new Error("HTTP error " + response.status);
                                            return response.json();
                                        })
                                        .then(data => {
                                            if (data.status === "success") {
                                                const detail = data.data;
                                                const detailHtml = `
                                                    <p><b>Kota:</b> ${detail.city_name}</p>
                                                    <p><b>Humidity:</b> ${detail.humidity || "Tidak tersedia"}%</p>
                                                    <p><b>AQI:</b> ${detail.aqi || "Tidak tersedia"}</p>
                                                    <hr>
                                                `;
                                                detailsDiv.innerHTML += detailHtml;
                                            } else {
                                                detailsDiv.innerHTML += `<p>Data untuk kota ${city} tidak ditemukan.</p>`;
                                            }
                                        })
                                        .catch(error => {
                                            console.error("Error:", error);
                                            detailsDiv.innerHTML = "<p>Terjadi kesalahan saat memuat detail tambahan.</p>";
                                        });
                                });

                                detailsDiv.style.display = "block";
                            }
                        </script>';
                    } else {
                        echo '<p class="error">Tidak ada data untuk temperatur ' . htmlspecialchars($temperature) . '°C.</p>';
                    }
                }?>
        </div>
    </div>
</body>
</html>
