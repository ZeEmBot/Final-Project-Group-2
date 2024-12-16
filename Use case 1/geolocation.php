<?php
$cityData = [
    "Jakarta" => ["latitude" => -6.2088, "longitude" => 106.8456],
    "Surabaya" => ["latitude" => -7.2575, "longitude" => 112.7521],
    "Bandung" => ["latitude" => -6.9175, "longitude" => 107.6191],
    "Tokyo" => ["latitude" => 35.6895, "longitude" => 139.6917],
    "New York" => ["latitude" => 40.7128, "longitude" => -74.0060],
    "Paris" => ["latitude" => 48.8566, "longitude" => 2.3522],
    "London" => ["latitude" => 51.5074, "longitude" => -0.1278],
    "Beijing" => ["latitude" => 39.9042, "longitude" => 116.4074],
    "Sydney" => ["latitude" => -33.8688, "longitude" => 151.2093],
    "Rio de Janeiro" => ["latitude" => -22.9068, "longitude" => -43.1729],
];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    header("Content-Type: application/json");
    $city = isset($_GET['city']) ? trim($_GET['city']) : null;

    if (!$city) {
        http_response_code(400);
        echo json_encode(["error" => "City parameter is required"]);
        exit;
    }

    if (array_key_exists($city, $cityData)) {
        echo json_encode([
            "city" => $city,
            "location" => $cityData[$city]
        ]);
    } else {
        http_response_code(404);
        echo json_encode(["error" => "City not found"]);
    }
    exit;
}
?>

