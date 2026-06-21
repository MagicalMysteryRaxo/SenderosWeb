<?php

$host = "localhost";
$user = "u873212447_usr_newchapter";
$password = "5yZK+IKbt1E=";
$database = "u873212447_dtb_newchapter";
$charset = 'utf8mb4';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    die("Database connection failed: " . $conn->connect_error);
}

?>