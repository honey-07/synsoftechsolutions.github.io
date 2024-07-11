<?php
session_start();

// Database credentials
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = "password"; // Replace with your MySQL password
$dbname = "feedback_db"; // Replace with your database name

// Establish connection to MySQL database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to sanitize input and prevent SQL injection
function sanitize_input($conn, $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $conn->real_escape_string($data);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = sanitize_input($conn, $_POST['username']);
    $password = sanitize_input($conn, $_POST['password']);

    // Hash the password for comparison with stored hash
    $hashed_password = md5($password);

    // Query to check if username and hashed password match an allowed entry
    $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$hashed_password'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Update last login time
        $update_login_sql = "UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE username = '$username'";
        $conn->query($update_login_sql);

        // Log login time into user_logs table
        $insert_log_sql = "INSERT INTO user_logs (username, login_time) VALUES ('$username', CURRENT_TIMESTAMP)";
        $conn->query($insert_log_sql);

        // Set session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username; // Store username in session

        // Redirect to the review page
        header("Location: review.php");
        exit;
    } else {
        // Redirect back to login page with error parameter
        header("Location: login.html?error=1");
        exit;
    }
}

$conn->close();
?>
