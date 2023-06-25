<?php
require_once  '../includes/db/db.php'; //database conn global file
/** @var mysqli $db */

$defaultError = 'Validation attempt unsuccessful';

$emailOrPhone = mysqli_escape_string($db, json_decode(file_get_contents('php://input'), true)['emailOrPhone']); // Get the email or phone number from the POST request
$password = mysqli_escape_string($db, json_decode(file_get_contents('php://input'), true)['password']); // Get the password from the POST request


if (isset($emailOrPhone)){ //validates login info, first checks if entered at all
    $query = "SELECT * FROM users WHERE email='$emailOrPhone' OR phone_number='$emailOrPhone'"; //checks if it matches any users entry in the email OR phone column,

    //retrieves pw hash from returned data, verifies, 
    //and then returns email, phone, password to app for later auto-login
    try{
        $result = mysqli_query($db, $query);

        if ($result) {
            $row = $result->fetch_object();
            if ($row != null) {
                $hash = $row->password;
                if (password_verify($password, $hash)){    
                    //sends data required to login back to client, to be saved into session for autologin.           
                    $userData = array('id' => $row->id, 'email' => $row->email, 'phone' => $row->phone_number);

                    header('Content-type: application/json');
                    echo json_encode($userData);
                } else {
                    http_response_code(400); // Set HTTP response code to 400 bad request
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

// Close the database connection
$db->close();