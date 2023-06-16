<?php
require_once '../db/db.php';
/** @var mysqli $db */

// $userId = mysqli_escape_string($db, json_decode(file_get_contents('php://input'), true)['userId']); // Get the label from the POST request

$query = "SELECT product_user.id, product_user.product_id, product_user.add_date, products.name, products.average_shelf_life
FROM product_user
INNER JOIN products ON product_user.product_id=products.id
WHERE product_user.user_id=1
ORDER BY product_user.product_id ASC, product_user.add_date ASC";
try {
    $result = mysqli_query($db, $query);

    $products = array();

    if ($result) {
        while($row = $result->fetch_assoc()){
            array_push($products, $row);
        }
        http_response_code(200); // Set HTTP response code to 200 (Success)
        header("Content-Type: application/json");
        echo json_encode($products);
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