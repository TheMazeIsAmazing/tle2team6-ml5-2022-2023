<?php
require_once '../db/db.php';
/** @var mysqli $db */


$label = mysqli_escape_string($db, json_decode(file_get_contents('php://input'), true)['label']); // Get the label from the POST request


echo 'Currently inserting ' . $label;

$query = "INSERT INTO `product_user` (`product_id`, `user_id`) VALUES ('$label', '1');";
$result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);

if ($result) {
    echo 'Label updated successfully';
} else {
    echo 'DIE'. $db->error;
}

//Execute the query
//if ($mysqli->query($query) === TRUE) {
//    echo 'Label updated successfully';
//} else {
//    echo 'Error updating label: ' . $mysqli->error;
//}

// Close the database connection
$db->close();
