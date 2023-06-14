<?php
    require_once  '../includes/db/db.php'; //database conn global file
    /** @var mysqli $db */
    
    //dummy method, 
    //idk how ur gonna request the data,
    //so replace all $_POST with appropriate method
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userId = testInput($_POST['userId']);
            $email = testInput($_POST['email']);
            $phone = testInput($_POST['phone']);

            $oldPassword = testInput($_POST['oldPassword']);
            $oldHash = password_hash($oldPassword, PASSWORD_DEFAULT);

            $newPassword = testInput($_POST['newPassword']);
            $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
            //ROXY TO DO: IF NEWHASH IS EMPTY OR MATCHES OLDHASH, REPLACE ITS VALUE WITH OLDHASH'S
            //this is in case user does not change their password

            $name = testInput($_POST['name']);
            $diet = testInput($_POST['diet']);
            $familySize = testInput($_POST['familySize']);

            $accessibilitySettings = testInput($_POST['accessibilitySettings']);

        if (isset($userId) && 
            isset($email) && 
            isset($phone) && 
            isset($oldHash) && 
            isset($newHash) && 
            isset($name) && 
            isset($diet) && 
            isset($familySize) && 
            isset($accessibilitySettings)) {

                //retrieves pw hash from data, verifies against oldHash, 
                //if success, loads insert.php to update db
                $query = "SELECT password FROM users WHERE id = " . $userId;
                $result = mysqli_query($conn, $query);
                $currentHash = mysqli_fetch_assoc($result);

                if (password_verify($oldHash, $currentHash)){

                    readfile('insert.php');
                } else { header("Location: ");}  //error     
        } else { header("Location: ");} //error
    }
    
    //parses input into nonviable characters. 
    //only htmlspecialchars here, seperate function to allow for additional parsing methods
    function testInput($data) {
        $data = htmlspecialchars($data);
        return $data;
      }