<?php
$conn=new mysqli('localhost','root','','sgn_girls_db');
if($conn->connect_error){
    die('connection end'.$conn->connect_error);
}
?>