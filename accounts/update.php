<?php
require_once  '../includes/db/db.php'; //database conn global file
/** @var mysqli $db */
$defaultError = 'Update attempt unsuccessful';

$userId = testInput('php://input', 'userId');
$email = testInput('php://input', 'email');
$phone = testInput('php://input', 'phone');

$oldPassword = testInput('php://input', 'oldPassword');
$oldHash = password_hash($oldPassword, PASSWORD_DEFAULT);

$newPassword = testInput('php://input', 'newPassword');
$newHash = password_hash($newPassword, PASSWORD_DEFAULT);

//makes sure the old hash is set. this should ALWAYS be the case during normal use.
//makes sure the new hash is not null, so hash does not get deleted from db
if($oldHash != null) {
    if ($newHash == null) {
        $newHash = $oldHash;
    }
} else {
    //error, old hash SHOULD NOT EVER BE EMPTY during normal use. abort db call.
    errorHandler('Validation attempt unsuccessful'); 
}

$name = testInput('php://input', 'name');
$familySize = testInput('php://input', 'familySize');

if (isset($userId) && 
    isset($email) && 
    isset($phone) && 
    isset($oldHash) && 
    isset($newHash) && 
    isset($name) && 
    isset($familySize)) {

        //retrieves pw hash from data, verifies against oldPassword, 
        //if success, makes sql request to update db
        $sql = sprintf(
            "SELECT password FROM users WHERE id =$userId");

        try{
            $result = mysqli_query($db, $sql);

            if ($result) {
                $row = $result->fetch_object();
                if ($row != null) {
                    $currentHash = $row->password;
                    if (password_verify($oldPassword, $currentHash)){
                       
                        //updates user info column based on id
                        $sql = sprintf(
                            "UPDATE users SET 
                                email='$email', 
                                phone_number='$phone', 
                                password='$newHash', 
                                user_name='$name', 
                                family_size='$familySize'
                            WHERE id='$userId'");

                        try{
                            $result = mysqli_query($db, $sql);

                            if ($result) {
                                //sends updated data required to login back to client, to be saved into session for autologin.           
                                $userData = array('email' => $email, 'phone' => $phone, 'password' => $newPassword);

                                header('Content-type: application/json');
                                echo json_encode($userData);
                            } else {
                                errorHandler($defaultError); //error
                            }
                        } catch (Exception $e) {
                            errorHandler($e->getMessage());
                        }

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