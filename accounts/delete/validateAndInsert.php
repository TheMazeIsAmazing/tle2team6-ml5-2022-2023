<?php
require_once  './connect.php'; //database conn global file

$userId = '';

//dummy method, 
//idk how ur gonna request the data,
//so replace all $_POST with appropriate method
//checks if userId was sent, checks if its integer-only
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userId']) && ctype_digit($_POST['userIdId'])) {
    $userId = testInput($_POST['customerId']);
} else {
    header("Location: "); //error
}

//removes column from table based on given id
$sql = sprintf(
    "DELETE FROM users WHERE id=$userId");

mysqli_query($conn, $sql) or die('error: '. mysqli_error($conn). ' with query'. $sql);

header("Location: "); //returns to app

//parses input into nonviable characters. 
//seperate function to allow for additional parsing methods
function testInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }