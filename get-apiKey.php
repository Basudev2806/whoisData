<?php
header('Content-Type: application/json');
require('config.php');
date_default_timezone_set("Asia/Calcutta");

if (isset($_GET['uname']) && isset($_GET['password'])) {
    $uname = $_GET['uname'];
    $password = $_GET['password'];
    $rows = mysqli_num_rows(mysqli_query($link,"SELECT * FROM user WHERE username = '$uname' AND password = 'md5($password)'"));
    if($rows == 1) {
        $data = mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM user WHERE username = '$uname' AND password = 'md5($password)'"));
        $apiKey = mysqli_fetch_assoc(mysqli_query($link,"SELECT * FROM apiKey WHERE uid = '$data[id]'"));
        
        $json = array('apiKey' => $apiKey['apiKey']);
    } else {
        $json = array('apiKey' => 'User Not Valid');
    }
    echo json_encode($json);
}

?>