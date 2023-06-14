<?php
        //creates new column in users table, using entered data
        $sql = sprintf(
        "INSERT INTO users (email, phone_number, password, user_name, diet, family_size, accessibility_settings) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s')",
        $conn->real_escape_string($email),
        $conn->real_escape_string($phone),
        $conn->real_escape_string($hash),
        $conn->real_escape_string($name),
        $conn->real_escape_string($diet),
        $conn->real_escape_string($familySize),
        $conn->real_escape_string($accessibilitySettings));
    mysqli_query($conn, $sql) or die('error: '. mysqli_error($conn). ' with query'. $sql);
    header("Location: "); 