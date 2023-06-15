<?php
require_once '../db/db.php';
/** @var mysqli $db */

$productUserId = mysqli_escape_string($db, json_decode(file_get_contents('php://input'), true)['productUserId']); // Get the product-user-id from the POST request
$expirationDate = mysqli_escape_string($db, json_decode(file_get_contents('php://input'), true)['expirationDate']); // Get the expiration-date from the POST request
$expirationDate = date('Y-m-d', strtotime($expirationDate));

if ($productUserId !== null && $productUserId !== '') {
    $query = "UPDATE `product_user` SET `expiration_date` = '$expirationDate' WHERE `product_user`.`id` = '$productUserId'";
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
} else {
    http_response_code(400); // Set HTTP response code to 400 (Bad request Error)
}