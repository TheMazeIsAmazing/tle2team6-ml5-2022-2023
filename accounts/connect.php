<?php

$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "fridge_friend";

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName) or die("Error: " . mysqli_connect_error());
