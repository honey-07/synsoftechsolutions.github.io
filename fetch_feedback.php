<?php
$servername = "localhost";
$username = "root";
$password = "password";
$dbname = "feedback_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT name, email, comments, rating, created_at FROM feedback";
$result = $conn->query($sql);

$feedback = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $feedback[] = $row;
    }
}

echo json_encode($feedback);

$conn->close();
?>
