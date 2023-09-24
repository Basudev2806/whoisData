<?php
header('Content-Type: application/json');
require('config.php');
date_default_timezone_set("Asia/Calcutta");

function getWhoisInfo($domain) {
    $sanitizedDomain = escapeshellarg($domain);
    $output = shell_exec('whois ' . $sanitizedDomain);

    return $output;
}

if (isset($_GET['domain']) && isset($_GET['apiKey'])) {
    $domain = $_GET['domain'];
    $apiKey = $_GET['apiKey'];
    
    // Define your secret API key
    $row = mysqli_num_rows(mysqli_query($link, "SELECT * FROM `domain_count` WHERE `apiKey` = '$apiKey' AND `remain` <= `total` AND `used` < `total`"));

    // Verify the API key
    if ($row == 1) {
        $whoisInfo = getWhoisInfo($domain);
        mysqli_fetch_assoc(mysqli_query($link, "UPDATE `domain_count` SET `remain`=`remain` - 1,`used`=`used` + 1 WHERE `apiKey` = '$apiKey'"));
        $response = $whoisInfo;
        $responseLines = explode("\n", $response);
        $whoisData = [];
        foreach ($responseLines as $line) {
            $parts = explode(": ", $line, 2);
            if (count($parts) === 2) {
                $key = trim($parts[0]);
                $value = trim($parts[1]);
                $whoisData[$key] = $value;
            }
        }
        echo json_encode($whoisData);
    } else {
        http_response_code(401); 
        echo json_encode(array('error' => 'Invalid API Key'));
    }
} else {
    echo 'Please provide both "domain" and "apiKey" parameters in the URL.';
}
?>
