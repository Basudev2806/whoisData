<?php
// Function to copy a website and create a ZIP file
function copyWebsiteToZip($sourceUrl, $zipFileName) {
    // Create a temporary directory to store the downloaded files
    $tempDir = sys_get_temp_dir() . '/website_copy';
    mkdir($tempDir);

    // Use wget to download the website recursively
    $wgetCommand = "wget --mirror --convert-links --adjust-extension --page-requisites --no-parent --span-hosts --accept-regex '.*\\.(html|css|js)$' -P $tempDir $sourceUrl";
    exec($wgetCommand);

    // Create a ZIP archive of the downloaded files
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

        // Clean up the temporary directory
        rrmdir($tempDir);

        return true;
    } else {
        return false;
    }
}

// Function to recursively remove a directory and its contents
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

// Usage example
$sourceUrl = 'https://google.com'; // Replace with the URL of the website you want to copy
$zipFileName = 'google.zip'; // Name of the ZIP file to create

if (copyWebsiteToZip($sourceUrl, $zipFileName)) {
    // Download the ZIP file
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipFileName . '"');
    readfile($zipFileName);

    // Delete the ZIP file
    unlink($zipFileName);

    exit;
} else {
    echo "Failed to copy website.";
}
?>
