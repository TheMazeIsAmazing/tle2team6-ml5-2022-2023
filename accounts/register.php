<?php
require_once  '../includes/db/db.php'; //database conn global file
/** @var mysqli $db */
$defaultError = 'Registration attempt unsuccessful';

$email = testInput('php://input', 'email');
$phone = testInput('php://input', 'phone');

$password = testInput('php://input', 'password');
$hash = password_hash($password, PASSWORD_DEFAULT);

$name = testInput('php://input', 'name');
$familySize = testInput('php://input', 'familySize');

//checks if all fields are entered,
//if true, loads insert.php to write to db
if (isset($email) && 
    isset($phone) && 
    isset($hash) && 
    isset($name) && 
    isset($familySize)) {

        //creates new column in users table, using entered data
        $sql = sprintf(
            "INSERT INTO users (email, phone_number, password, user_name, family_size) 
            VALUES ('$email', '$phone', '$hash', '$name', '$familySize')"
        );
            
        try{
            $result = mysqli_query($db, $sql);
        
            if ($result) {
                http_response_code(200); // Set HTTP response code to 200 (Success)
                echo 'Registered successfully';
            } else {
                errorHandler($defaultError); //error
            }
        } catch (Exception $e) {
            errorHandler($e->getMessage());
        }
    } else { errorHandler($defaultError);} //error

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