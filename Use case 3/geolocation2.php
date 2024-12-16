<?php
$cityData = [
    "Jakarta" => ["latitude" => -6.2088, "longitude" => 106.8456, "aqi" => 155],
    "Surabaya" => ["latitude" => -7.2575, "longitude" => 112.7521, "aqi" => 135],
    "Bandung" => ["latitude" => -6.9175, "longitude" => 107.6191, "aqi" => 85],
    "Tokyo" => ["latitude" => 35.6895, "longitude" => 139.6917, "aqi" => 45],
    "New York" => ["latitude" => 40.7128, "longitude" => -74.0060, "aqi" => 65],
    "Paris" => ["latitude" => 48.8566, "longitude" => 2.3522, "aqi" => 55],
    "London" => ["latitude" => 51.5074, "longitude" => -0.1278, "aqi" => 50],
    "Beijing" => ["latitude" => 39.9042, "longitude" => 116.4074, "aqi" => 180],
    "Sydney" => ["latitude" => -33.8688, "longitude" => 151.2093, "aqi" => 25],
    "Rio de Janeiro" => ["latitude" => -22.9068, "longitude" => -43.1729, "aqi" => 75],
];

class GeoLocationService {
    public function getLocationByCity($city) {
        global $cityData;
        if (isset($cityData[$city])) {
            return json_encode($cityData[$city]);
        } else {
            throw new SoapFault("Server", "City not found");
        }
    }
}

$options = ['uri' => 'http://localhost'];
$server = new SoapServer(null, $options);
$server->setClass("GeoLocationService");
$server->handle();
?>
