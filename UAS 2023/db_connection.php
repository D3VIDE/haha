<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_uastekweb"; // Nama DB


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>