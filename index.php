<?php
header('Content-Type: application/json');
date_default_timezone_set("Asia/Calcutta");

function getWhoisInfo($domain) {
    $sanitizedDomain = escapeshellarg($domain);
    $output = shell_exec('whois ' . $sanitizedDomain);
    return $output;
}

function getTraceroute($domain) {
    $sanitizedDomain = escapeshellarg($domain);
    $output = shell_exec('traceroute ' . $sanitizedDomain);
    return $output;
}

function getPingInfo($domain, $count = 5) {
    $sanitizedDomain = escapeshellarg($domain);
    $output = shell_exec("ping -c $count $sanitizedDomain");
    return $output;
}

function getGeolocationInfo($ip) {
    $url = "http://ip-api.com/json/{$ip}";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response);

    return $data;
}

if (isset($_GET['domain'])) {
    $domain = $_GET['domain'];
    $whoisInfo = getWhoisInfo($domain);
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
}

if (isset($_GET['trace'])) {
    $domain = $_GET['trace'];
    $tracerouteInfo = getTraceroute($domain);
    $responseLines = explode("\n", $tracerouteInfo);
    $formattedTracerouteData = [];
    foreach ($responseLines as $line) {
        $line = preg_replace('/ec2-\S+/', 'whoisdata.tech ', $line);
        $line = trim($line);
        if (!empty($line)) {
            $formattedTracerouteData[] = $line;
        }
    }
    echo json_encode($formattedTracerouteData);
}

if (isset($_GET['ping'])) {
    $domain = $_GET['ping'];
    $pingInfo = getPingInfo($domain);
    $responseLines = explode("\n", $pingInfo);
    
    $pingData = [
        "ping_summary" => [],
        "ping_statistics" => []
    ];

    foreach ($responseLines as $line) {
        // Remove empty lines
        $line = trim($line);
        if (!empty($line)) {
            // Check if the line contains ping statistics
            if (strpos($line, "ping statistics") !== false) {
                $pingData["ping_statistics"][] = $line;
            } else {
                $pingData["ping_summary"][] = $line;
            }
        }
    }
    echo json_encode($pingData);
}

if (isset($_GET['ip'])) {
    $domain = $_GET['ip'];
    $ipInfo = getGeolocationInfo($domain);
    $response = $ipInfo;
    echo json_encode($response);
}

?>
