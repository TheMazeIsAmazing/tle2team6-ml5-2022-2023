<?php
require_once  '../includes/db/db.php'; //database conn global file
/** @var mysqli $db */

$emailOrPhone = testInput('php://input', 'emailOrPhone');  
$password = testInput('php://input', 'password');


if (isset($emailOrPhone)){ //validates login info, first checks if entered at all
$sql = sprintf(
    "SELECT * FROM users WHERE email=$emailOrPhone OR phone_number=$emailOrPhone"); //checks if it matches any users entry in the email OR phone column,

    //retrieves pw hash from returned data, verifies, 
    //and then returns email, phone, password to app for later auto-login
    try{
        $result = mysqli_query($db, $sql);

        if ($result) {
            $row = $result->fetch_object();
            if ($row != null) {
                $hash = $row->password;
                if (password_verify($password, $hash)){
                    //ROXY TO DO: SESSION SERVERSIDE IS IRRELEVANT. 
                    //CHANGE TO SENDING USER DATA BACK TO APP. 
                    //SO IT CAN BE STORED IN SESSION THERE
                    
                    http_response_code(200); // Set HTTP response code to 200 (Success)
                    echo 'Relation deleted successfully';
                } else {
                    errorHandler('Validation attempt unsuccessful'); //error
                }
            } else {
                errorHandler('Validation attempt unsuccessful'); //error
            }           
        } else {
            errorHandler('Validation attempt unsuccessful'); //error
        }
    } catch (Exception $e) {
        errorHandler($e->getMessage());
    }
     
} else { header("Location: ");} //error

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