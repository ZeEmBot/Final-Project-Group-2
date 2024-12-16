<?php
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
            return [
                "city" => $city,
                "temperature" => $this->temperatureData[$city]
            ];
        } else {
            throw new SoapFault("Server", "City not found");
        }
    }

    public function getWeatherWithinRadius($city, $radius) {
        $geoLocationClient = new SoapClient(null, ['location' => 'http://localhost/nt/geolocation2.php', 'uri' => 'http://localhost']);
        $citiesInRadius = $geoLocationClient->getCitiesWithinRadius($city, $radius);

        $result = [];
        foreach ($citiesInRadius as $cityData) {
            $cityName = $cityData['city'];
            if (isset($this->temperatureData[$cityName])) {
                $result[] = [
                    "city" => $cityName,
                    "temperature" => $this->temperatureData[$cityName],
                    "distance" => $cityData['distance']
                ];
            }
        }
        return $result;
    }
}

$options = ['uri' => 'http://localhost'];
$server = new SoapServer(null, $options);
$server->setClass("WeatherService");
$server->handle();
?>
