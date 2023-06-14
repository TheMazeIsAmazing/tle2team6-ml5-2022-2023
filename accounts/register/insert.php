<?php
        //creates new column in users table, using entered data
        $sql = sprintf(
        "INSERT INTO users (email, phone_number, password, user_name, family_size) VALUES ('%s', '%s', '%s', '%s', '%s')",
        $conn->real_escape_string($email),
        $conn->real_escape_string($phone),
        $conn->real_escape_string($hash),
        $conn->real_escape_string($name),
        $conn->real_escape_string($familySize));
    mysqli_query($conn, $sql) or die('error: '. mysqli_error($conn). ' with query'. $sql);
    header("Location: "); 