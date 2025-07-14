<?php
$conn = new mysqli('localhost', 'root', '', 'sgn_girl_admission');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>