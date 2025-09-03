<?php
$conn = new mysqli('localhost', 'root', '', 'sgn_law_college');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


?>