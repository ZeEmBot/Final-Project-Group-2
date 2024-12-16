<?php
$temperatureData = [
    "Jakarta" => 30,
    "Surabaya" => 32,
    "Bandung" => 20,
    "Tokyo" => 15,
    "New York" => 10,
    "Paris" => 12,
    "London" => 14,
    "Beijing" => 8,
    "Sydney" => 20,
    "Rio de Janeiro" => 28,
];

$geoLocationUrl = 'http://localhost/ol/geolocation2.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['city'])) {
        $city = $_GET['city'];
        header("Content-Type: application/json");

        $locationResponse = file_get_contents($geoLocationUrl . '?city=' . urlencode($city));
        
        if ($locationResponse !== false) {
            $locationData = json_decode($locationResponse, true);
            
            if (isset($locationData['location'])) {
                $temperature = isset($temperatureData[$city]) ? $temperatureData[$city] : null;

                if ($temperature !== null) {
                    echo json_encode([
                        "city" => $city,
                        "location" => $locationData['location'],
                        "temperature" => $temperature
                    ]);
                } else {
                    http_response_code(404);
                    echo json_encode(["error" => "Temperature data not found"]);
                }
            } else {
                http_response_code(404);
                echo json_encode(["error" => "City not found in GeoLocation Service"]);
            }
        } else {
            http_response_code(500);
            echo json_encode(["error" => "Unable to connect to GeoLocation Service"]);
        }
        exit;
    } elseif (isset($_GET['temperature'])) {
        $selectedTemperature = intval($_GET['temperature']);
        $matchingCities = [];

        foreach ($temperatureData as $city => $temperature) {
            if ($temperature == $selectedTemperature) {
                $matchingCities[$city] = $temperature;
            }
        }

        if (!empty($matchingCities)) {
            echo "<h2>Kota dengan temperatur $selectedTemperature °C:</h2>";
            echo "<ul>";
            foreach ($matchingCities as $city => $temperature) {
                $locationResponse = file_get_contents($geoLocationUrl . '?city=' . urlencode($city));
                $locationData = json_decode($locationResponse, true);
                echo "<li>$city - Temperatur: $temperature °C, Lokasi: " . json_encode($locationData['location']) . "</li>";
            }
            echo "</ul>";
        } else {
            echo "<h2>Tidak ada kota dengan temperatur $selectedTemperature °C ditemukan.</h2>";
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informasi Cuaca Kota</title>
</head>
<body>
    <h1>Pilih Kota untuk Melihat Informasi Cuaca</h1>
    <form method="GET" action="weather2.php">
        <label for="city">Pilih Kota:</label>
        <select name="city" id="city">
            <?php foreach ($temperatureData as $city => $temperature): ?>
                <option value="<?php echo htmlspecialchars($city); ?>"><?php echo htmlspecialchars($city); ?></option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Lihat Cuaca</button>
    </form>

    <h1>Pilih Temperatur untuk Melihat Kota</h1>
    <form method="GET" action="weather2.php">
        <label for="temperature">Pilih Temperatur:</label>
        <select name="temperature" id="temperature">
            <?php foreach (array_unique($temperatureData) as $temperature): ?>
                <option value="<?php echo htmlspecialchars($temperature); ?>"><?php echo htmlspecialchars($temperature); ?> °C</option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Lihat Kota</button>
    </form>
</body>
</html>