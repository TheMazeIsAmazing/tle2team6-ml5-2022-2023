<?php
require_once '../db/db.php';
/** @var mysqli $db */

$label = mysqli_escape_string($db, json_decode(file_get_contents('php://input'), true)['label']); // Get the label from the POST request

$query = "INSERT INTO `product_user` (`product_id`, `user_id`) VALUES ('$label', '1');";
try {
    $result = mysqli_query($db, $query);

    if ($result) {
        http_response_code(200); // Set HTTP response code to 200 (Success)
        echo 'Relation inserted successfully';
    } else {
        http_response_code(500); // Set HTTP response code to 500 (Internal Server Error)
        echo 'Relation inserted unsuccessful';
    }
} catch (Exception $e) {
    http_response_code(500); // Set HTTP response code to 500 (Internal Server Error)
    echo 'An error occurred: ' . $e->getMessage();
}

// Close the database connection
$db->close();