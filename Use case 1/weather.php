<?php
$temperatureData = [
    "Jakarta" => 30,
    "Surabaya" => 32,
    "Bandung" => 25,
    "Tokyo" => 15,
    "New York" => 10,
    "Paris" => 12,
    "London" => 14,
    "Beijing" => 8,
    "Sydney" => 20,
    "Rio de Janeiro" => 28,
];

$geoLocationUrl = 'http://localhost/api/geolocation.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Content-Type: application/json");

    $input = json_decode(file_get_contents("php://input"), true);

    if (!isset($input['city']) || empty(trim($input['city']))) {
        http_response_code(400);
        echo json_encode(["error" => "City parameter is required in the request body"]);
        exit;
    }

    $city = trim($input['city']);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $geoLocationUrl . '?city=' . urlencode($city));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);

    $locationResponse = curl_exec($curl);

    if ($locationResponse === false) {
        http_response_code(500);
        echo json_encode(["error" => "Unable to connect to GeoLocation Service"]);
        curl_close($curl);
        exit;
    }

    curl_close($curl);

    $locationData = json_decode($locationResponse, true);

    if (!isset($locationData['location'])) {
        http_response_code(404);
        echo json_encode(["error" => "City not found in GeoLocation Service"]);
        exit;
    }

    $temperature = $temperatureData[$city] ?? null;
    if ($temperature === null) {
        http_response_code(404);
        echo json_encode(["error" => "Temperature data not found"]);
        exit;
    }

    echo json_encode([
        "city" => $city,
        "location" => $locationData['location'],
        "temperature" => $temperature
    ]);
    exit;
}
?>
