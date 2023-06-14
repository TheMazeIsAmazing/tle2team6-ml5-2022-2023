<?php
require_once '../db/db.php';
/** @var mysqli $db */

$userId = mysqli_escape_string($db, json_decode(file_get_contents('php://input'), true)['userId']); // Get the label from the POST request

$query = "SELECT product_user.id, product_user.product_id, products.name, products.image, products.average_shelf_life
FROM product_user
INNER JOIN products ON product_user.product_id=products.id
WHERE product_user.user_id='$userId'
ORDER BY product_user.product_id ASC";
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