<?php
// DB connection
$conn = new mysqli("localhost", "root", "", "rebeltech");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$email = $_POST['email'];
$password = $_POST['password'];

// Find user
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Verify user
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        echo "Login successful. Welcome " . $user['full_name'] . "!";
        // session_start(); $_SESSION['user'] = $user; // if needed
        // header("Location: dashboard.php");
    } else {
        echo "Invalid password.";
    }
} else {
    echo "No user found with that email.";
}

$conn->close();
?>
