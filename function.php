<?php
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

function copyWebsiteToZip($sourceUrl) {
    $tempDir = sys_get_temp_dir() . '/website_copy';
    mkdir($tempDir);
    $domain = str_replace('.', '-', $sourceUrl);
    $zipFileName = "saveweb2zip-techbanda-com-{$domain}" . '.zip';
    $wgetCommand = "wget --mirror --convert-links --adjust-extension --page-requisites --no-parent --span-hosts --accept-regex '.*\\.(html|css|js)$' -P $tempDir $sourceUrl";
    exec($wgetCommand);

    $zip = new ZipArchive();
    if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($tempDir),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($tempDir) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        rrmdir($tempDir);

        return true;
    } else {
        return false;
    }
}

function rrmdir($dir) {
    foreach (glob($dir . '/*') as $file) {
        if (is_dir($file)) {
            rrmdir($file);
        } else {
            unlink($file);
        }
    }
    rmdir($dir);
}

function generatePDF($htmlContent) {
    $tempFile = tempnam(sys_get_temp_dir(), 'pdf');
    file_put_contents($tempFile, $htmlContent);
    $outputFile = "html2pdf-techbanda-com-output" . '.pdf';
    $command = "wkhtmltopdf $tempFile $outputFile";
    exec($command);
    unlink($tempFile);
}

function convertVideo($inputFile, $format = 'mp4') {
    $outputFile = "converted-video-techbanda-com" . '.mp4';
    $command = "ffmpeg -i $inputFile -c:v $format $outputFile";
    exec($command);
}

?>