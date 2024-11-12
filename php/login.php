<?php

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_info";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve form data
$username = mysqli_real_escape_string($conn, $_POST['fname']);
$password = $_POST['password'];

// Check if the user exists
$sql = "SELECT * FROM user_database WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (password_verify($password, $row['password'])) {
        echo "<script>alert('Login successful!'); window.location.href = '../html/logging.html';</script>";
    } else {
        echo "<script>alert('Invalid password.'); window.location.href = 'index.html';</script>";
    }
} else {
    echo "<script>alert('User not found.'); window.location.href = 'index.html';</script>";
}

// Close connection
$conn->close();
?>