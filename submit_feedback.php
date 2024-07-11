<?php
$servername = "localhost"; // Replace with your MySQL server name
$username = "root"; // Replace with your MySQL username
$password = "password"; // Replace with your MySQL password
$dbname = "feedback_db"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO feedback (name, email, comments, overall_satisfaction, project_quality, timeliness, professionalism, value_for_money, like_most, dislike_most, improve, additional_comments) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssssssss", $name, $email, $comments, $overall_satisfaction, $project_quality, $timeliness, $professionalism, $value_for_money, $like_most, $dislike_most, $improve, $additional_comments);

// Set parameters and execute
$name = $_POST['name'];
$email = $_POST['email'];
$comments = $_POST['comments'];
$overall_satisfaction = $_POST['rating'];
$project_quality = $_POST['Proj-Qual'];
$timeliness = $_POST['timeliness'];
$professionalism = $_POST['professionalism'];
$value_for_money = $_POST['valueForMoney'];
$like_most = $_POST['like'];
$dislike_most = $_POST['dislike'];
$improve = $_POST['improve'];
$additional_comments = $_POST['additional'];

if ($stmt->execute()) {
    echo "New record created successfully";
    echo '<br><button onclick="window.location.href=\'login.html\'">Review</button>';
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
