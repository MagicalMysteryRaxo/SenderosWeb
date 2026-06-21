<?php

$host = "localhost";
$user = "u873212447_usr_newchapter";
$password = "N^2RJ:9:Rkxy";
$database = "u873212447_dtb_newchapter";


$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    die("Database connection failed: " . $conn->connect_error);
}

?>