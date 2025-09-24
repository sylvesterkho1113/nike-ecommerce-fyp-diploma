<?php
session_start();
$conn= mysqli_connect("localhost","root","","final_year_project") or die;// fill out database name

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>