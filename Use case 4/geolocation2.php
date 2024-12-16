<?php
class GeoLocationService {
    private $cityData = [
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

    public function getCityLocation($city) {
        if (isset($this->cityData[$city])) {
            return $this->cityData[$city];
        } else {
            throw new SoapFault("Server", "City not found");
        }
    }

    public function getCitiesWithinRadius($city, $radius) {
        if (!isset($this->cityData[$city])) {
            throw new SoapFault("Server", "City not found");
        }

        $cityLocation = $this->cityData[$city];
        $result = [];
        foreach ($this->cityData as $key => $data) {
            $distance = $this->calculateDistance(
                $cityLocation['latitude'],
                $cityLocation['longitude'],
                $data['latitude'],
                $data['longitude']
            );
            if ($distance <= $radius) {
                $result[] = [
                    "city" => $key,
                    "distance" => $distance,
                    "latitude" => $data['latitude'],
                    "longitude" => $data['longitude']
                ];
            }
        }
        return $result;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; 
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}

$options = ['uri' => 'http://localhost'];
$server = new SoapServer(null, $options);
$server->setClass("GeoLocationService");
$server->handle();
?>
