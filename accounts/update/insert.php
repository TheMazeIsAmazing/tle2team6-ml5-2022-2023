<?php
    //updates user info column based on id
    $sql = sprintf(
        "UPDATE users SET 
            email='%s', 
            phone_number='%s', 
            password='%s', 
            user_name='%s', 
            diet='%s', 
            family_size='%s', 
            accessibility_settings='%s' 
        WHERE id='%s'",
        $conn->real_escape_string($email),
        $conn->real_escape_string($phone),
        $conn->real_escape_string($newHash),
        $conn->real_escape_string($name),
        $conn->real_escape_string($diet),
        $conn->real_escape_string($familySize),
        $conn->real_escape_string($accessibilitySettings),
        $conn->real_escape_string($userId));
    mysqli_query($conn, $sql) or die('error: '. mysqli_error($conn). ' with query'. $sql);
    header("Location: ");