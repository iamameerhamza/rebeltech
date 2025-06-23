<?php
header('Content-Type: application/json');

// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// DB connection
$conn = new mysqli('localhost', 'root', '', 'rebeltech');

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed']);
    exit();
}

// Fetch products
$sql = "SELECT id, name, description, price, image_url FROM products";
$result = $conn->query($sql);

if (!$result) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Query failed']);
    exit();
}

$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

echo json_encode([
    'status' => 'success',
    'data' => $products
]);
?>
