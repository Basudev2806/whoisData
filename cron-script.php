<?php
header('Content-Type: application/json');
require('config.php');
date_default_timezone_set("Asia/Calcutta");

mysqli_fetch_assoc(mysqli_query($link,"UPDATE `domain_count` SET `remain`=50000,`used`=0,`total`=50000 WHERE 1"))

?>