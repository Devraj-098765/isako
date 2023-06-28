<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: *');

// Connect to database
$mysqli = new mysqli("sql205.epizy.com", "epiz_34239581", "VyWrsjdaSprX","epiz_34239581_devvv");

// Check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}

// Select the database
mysqli_query($mysqli, 'use epiz_34239581_devvv');

// Execute SQL query
$result = $mysqli->query('SELECT * FROM weather');

// Check if query execution was successful
if (!$result) {
    echo "Error executing SQL query: " . $mysqli->error;
    exit();
}

// Get data, convert to JSON, and print
$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);

$result->free(); // Free the result set
$mysqli->close(); // Close the database connection
?>