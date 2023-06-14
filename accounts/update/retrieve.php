<?php
    require_once  './connect.php'; //database conn global file

    //dummy method, 
    //idk how ur gonna request the data,
    //so replace all $_POST with appropriate method
    $userId = $_POST['id'];

    //retrieves userdata from id
    $query = "SELECT * FROM users WHERE id = " . $userId;
    $result = mysqli_query($conn, $query);
    $userData = mysqli_fetch_assoc($result);

    $email = testInput($userData['email']);
    $phone = testInput($userData['phone']);

    //dont pull password hash obvs, but dont forget to allow updating it still, requiring old password

    $name = testInput($userData['name']);
    $diet = testInput($userData['diet']);
    $familySize = testInput($userData['familySize']);

    $accessibilitySettings = testInput($userData['accessibilitySettings']);

    header("Location: "); //success
    //ROXY TO DO: ADD FAILURE STATE. WHICH DOES NOT ALLOW PROFILE EDIT ACCESS

    //parses input into nonviable characters. 
    //seperate function to allow for additional parsing methods
    function testInput($data) {
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
      }