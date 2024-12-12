<?
class GoogleMapsAPI {
    private $apiKey;

    public function __construct($apiKey) {
        $this->apiKey = $apiKey;
    }

    public function getDirections($origin, $destination) {
        $url = "https://maps.googleapis.com/maps/api/directions/json?origin={$origin}&destination={$destination}&key={$this->apiKey}";
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    public function getLocationCoordinates($address) {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($address) . "&key={$this->apiKey}";
        $response = file_get_contents($url);
        return json_decode($response, true);
    }
}
?>