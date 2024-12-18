<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Website</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Weather Website</h1>
        <p>Website ini memberikan informasi cuaca dan kualitas udara berdasarkan nama kota yang dimasukkan.</p>
        
        <!-- Form untuk mencari data cuaca -->
        <form method="GET" action="">
            <label for="city_name">Masukkan Nama Kota:</label>
            <input type="text" id="city_name" name="city_name" placeholder="Contoh: Jakarta" required>
            <button type="submit">Cari</button>
        </form>

        <?php
        include '../geolocation/db.php';

        if (isset($_GET['city_name'])) {
            $city_name = $conn->real_escape_string($_GET['city_name']);
            $sql = "SELECT * FROM weather WHERE city_name LIKE '%$city_name%'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<h2>Hasil Pencarian:</h2>";
                echo "<table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama Kota</th>
                                <th>Temperatur</th>
                                <th>Kelembapan</th>
                                <th>Indeks Kualitas Udara (AQI)</th>
                            </tr>
                        </thead>
                        <tbody>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                            <td>{$row['id']}</td>
                            <td>{$row['city_name']}</td>
                            <td>{$row['temperature']} °C</td>
                            <td>{$row['humidity']}%</td>
                            <td>{$row['air_quality_index']}</td>
                          </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "<p>Data untuk kota '<strong>$city_name</strong>' tidak ditemukan.</p>";
            }
        }

        $conn->close();
        ?>
    </div>
</body>
</html>
