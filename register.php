<?php
header('Content-Type: application/json');
require('config.php');
date_default_timezone_set("Asia/Calcutta");

function generateApiKey($length = 32) {
    return bin2hex(random_bytes($length));
}

if (isset($_GET['uname']) && isset($_GET['password'])) {
    $uname = $_GET['uname'];
    $password = $_GET['password'];
    $rows = mysqli_num_rows(mysqli_query($link,"SELECT * FROM user WHERE username = '$uname' AND password = 'md5($password)'"));
    if($rows == 0) {
        $data = mysqli_fetch_assoc(mysqli_query($link,"INSERT INTO `user`(`username`, `password`, `pass`) VALUES ($uname,md5($password),$password)"));
        
        if($data) {
            $apiKey = generateApiKey();
            $udata = mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM user WHERE username = '$uname' AND password = 'md5($password)'"));
            mysqli_fetch_assoc(mysqli_query($link,"INSERT INTO `apiKey`(`uid`, `apiKey`) VALUES ($udata[id],$apiKey)"));
            mysqli_fetch_assoc(mysqli_query($link,"INSERT INTO `domain_count`(`apiKey`, `remain`, `used`, `total`) VALUES ($apiKey,50000,0,50000)"));
        }
        
        $json = array('apiKey' => $apiKey['apiKey']);
    } else {
        $json = array('apiKey' => 'User Not Valid');
    }
    echo json_encode($json);
}

?>