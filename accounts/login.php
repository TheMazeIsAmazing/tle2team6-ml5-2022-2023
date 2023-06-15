<?php
require_once  '../includes/db/db.php'; //database conn global file
/** @var mysqli $db */
$defaultError = 'Validation attempt unsuccessful';

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
                    //sends data required to login back to client, to be saved into session for autologin.           
                    $userData = array('email' => $row->email, 'phone' => $row->$phone, 'password' => $password);

                    header('Content-type: application/json');
                    echo json_encode($userData);
                } else {
                    errorHandler($defaultError); //error
                }
            } else {
                errorHandler($defaultError); //error
            }           
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