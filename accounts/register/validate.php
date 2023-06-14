<?php
    require_once './connect.php'; //database conn global file
    
    //dummy method, 
    //idk how ur gonna request the data,
    //so replace all $_POST with appropriate method
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $email = testInput($_POST['email']);
        $phone = testInput($_POST['phone']);

        $password = testInput($_POST['password']);
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $name = testInput($_POST['name']);
        $diet = testInput($_POST['diet']);
        $familySize = testInput($_POST['familySize']);

        $accessibilitySettings = testInput($_POST['accessibilitySettings']);

        //checks if all fields are entered,
        //if true, loads insert.php to write to db
        if (isset($email) && 
            isset($phone) && 
            isset($hash) && 
            isset($name) && 
            isset($diet) && 
            isset($familySize) && 
            isset($accessibilitySettings)) {
                readfile('insert.php');
        }
    } else { header("Location: ");} //error

    //parses input into nonviable characters. 
    //only htmlspecialchars here, seperate function to allow for additional parsing methods
    function testInput($data) {
        $data = htmlspecialchars($data);
        return $data;
      }