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

class WeatherService {
    private $temperatureData = [
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

    public function getWeatherByCity($city) {
        if (isset($this->temperatureData[$city])) {
            return json_encode([
                "city" => $city,
                "temperature" => $this->temperatureData[$city]
            ]);
        } else {
            throw new SoapFault("Server", "City not found");
        }
    }
}

$options = ['uri' => 'http://localhost'];
$server = new SoapServer(null, $options);
$server->setClass("WeatherService");
$server->handle();
?>
