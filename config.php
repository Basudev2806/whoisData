<?php 
date_default_timezone_set("Asia/Calcutta");
///header('Content-Type:application/json');
$servername = "localhost";
$username = "sql_whoisdata_te";
$password = "Basudev@2806";
$dbname = "sql_whoisdata_te";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
//$link = new mysqli($servername, $username, $password, $dbname);
$link=mysqli_connect($servername, $username, $password, $dbname);
//PDO
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);

// Check connection
if ($conn->connect_error) 
{
    die("Connection failed: " . $conn->connect_error);
}

?>