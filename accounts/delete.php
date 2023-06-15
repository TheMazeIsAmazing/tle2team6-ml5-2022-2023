<?php
require_once  '../includes/db/db.php'; //database conn global file
/** @var mysqli $db */
$defaultError = 'Account delete unsuccessful';

$userId = testInput('php://input', 'userId');

//checks if userId was sent, checks if its integer-only
if(isset($userId) && ctype_digit($userId)) {

    //removes column from users table based on given id, 
    //and also deletes product_user columns associated with said user
    $sql = sprintf(
        "DELETE FROM users INNER JOIN product_user WHERE users.id= product_user.user_id and users.id=$userId");
    
    try{
        $result = mysqli_query($db, $sql);
    
        if ($result) {
            http_response_code(200); // Set HTTP response code to 200 (Success)
            echo 'Relation deleted successfully';
        } else {
            errorHandler($defaultError);
        }
    } catch (Exception $e) {
        errorHandler($e->getMessage());
    }
} else {
    errorHandler($defaultError);
}

function errorHandler($errorMessage) {
    http_response_code(500); // Set HTTP response code to 500 (Internal Server Error)
        echo 'An error occurred: ' . $errorMessage;
}

//parses JSON into seperate strings, and escapes it. 
function testInput($data, $paramName) {
    $data = file_get_contents($data);
    $data = json_decode($data, true)[$paramName];
    $data = mysqli_escape_string($db, $data);
    return $data;
  }

  // Close the database connection
$db->close();