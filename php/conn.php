<?php
$host = "localhost";         // or your DB host
$username = "root";          // your DB username
$password = "";              // your DB password
$database = "rebeltech";     // your DB name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("❌ Connection failed: " . $conn->connect_error);
}

// Set character set to utf8mb4
$conn->set_charset("utf8mb4");

// Optional: force InnoDB usage (MySQL uses it by default, but you can check)
$result = $conn->query("SELECT @@default_storage_engine") or die($conn->error);
$row = $result->fetch_row();

if (strtolower($row[0]) !== 'innodb') {
    die("❌ InnoDB is not set as the default storage engine.");
}

echo "✅ Connected successfully to the RebelTech database using InnoDB!";
?>
