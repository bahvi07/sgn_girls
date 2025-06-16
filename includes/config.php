<?php
$conn = new mysqli('localhost', 'root', '', 'sgn_girl_admission');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to handle Hindi text properly
$conn->set_charset("utf8mb4");
?>