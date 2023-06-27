<?php
require_once  '../includes/db/db.php'; //database conn global file
/** @var mysqli $db */
$defaultError = 'user data retrieval attempt unsuccessful';

$userId = testInput('php://input', 'userId');

//retrieves userdata from id
$sql = sprintf( 
    "SELECT * FROM users WHERE id ='$userId'");

try{
  $result = mysqli_query($db, $sql);

  if ($result) {
    $row = $result->fetch_object();
    if ($row != null) {
      //removes the password hash from retrieved data so it cant be stolen. 
      //frontenders be sure to input the password from the session to allow updating and verification
      $row = array_slice($row, 3, 1);

      header('Content-type: application/json');
      echo json_encode($row);
    } else {
      errorHandler($defaultError); //error
    }
  } else {
    errorHandler($defaultError); //error
  }
} catch (Exception $e) {
  errorHandler($e->getMessage());
} 
//ROXY TO DO: ADD FAILURE STATE. WHICH DOES NOT ALLOW PROFILE EDIT ACCESS

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