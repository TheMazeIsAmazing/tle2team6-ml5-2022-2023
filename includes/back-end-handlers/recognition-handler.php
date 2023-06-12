<?php
$label = $_POST['label']; // Get the label from the POST request

require_once './includes/db/db.php';
/** @var mysqli $db */

echo $label;

//$query = "INSERT * FROM reserveringen";
////Get the result set from the database with a SQL query
//$result = mysqli_query($db, $query); //or die('Error: ' . mysqli_error($db) . ' with query ' . $query);

//// Database connection details
//$host = 'localhost';
//$user = 'root';
//$password = '';
//$database = 'epic_name';
//
//// Create a new MySQLi object and establish the database connection
//$mysqli = new mysqli($host, $user, $password, $database);
//
//// Check the connection status
//if ($mysqli->connect_error) {
//    die('Connection failed: ' . $mysqli->connect_error);
//}
//
//// Prepare the SQL query to update the labels column in the epic_table
//$query = "UPDATE epic_table SET labels = '$label'";
//
//// Execute the query
//if ($mysqli->query($query) === TRUE) {
//    echo 'Label updated successfully';
//} else {
//    echo 'Error updating label: ' . $mysqli->error;
//}

//// Close the database connection
//$mysqli->close();
