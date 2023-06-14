<?php
    session_start();
    require_once './connect.php'; //database conn global file

    if ($_SERVER["REQUEST_METHOD"] == "POST") {             //dummy method, 
        $emailOrPhone = testInput($_POST['emailOrPhone']);  //idk how ur gonna request the data,
        $password = testInput($_POST['password']);          //so replace all $_POST with appropriate

        //validates login info, first checks if entered at all, 
        //then checks if it matches any users entry in the email OR phone column, 
        //and returns userdata it if true
        if (isset($emailOrPhone)){ 
           $sql = sprintf(
            "SELECT * FROM users WHERE email='%s' OR phone_number='%s'",
            $conn->real_escape_string($emailOrPhone),
            $conn->real_escape_string($emailOrPhone));

            //retrieves pw hash from data, verifies, 
            //and then enters email and phone into session for later verification
            $result = $conn->query($sql);
            $row = $result->fetch_object();
            if ($row != null) {
                $hash = $row->hash;
                if (password_verify($password, $hash)){
                    //ROXY TO DO: SESSION SERVERSIDE IS IRRELEVANT. 
                    //CHANGE TO SENDING USER DATA BACK TO APP. 
                    //SO IT CAN BE STORED IN SESSION THERE
                    $_SESSION['email'] = $row->email;
                    $_SESSION['phone'] = $row->phone_number;
                    header("Location: "); //success
                } else {
                    header("Location: "); //error
                }
            } else {
                header("Location: "); //error
            } 
        } else { header("Location: ");} //error
    } else { header("Location: ");} //error

    //parses input into nonviable characters. 
    //only htmlspecialchars here, seperate function to allow for additional parsing methods
    function testInput($data) {
        $data = htmlspecialchars($data);
        return $data;
      }