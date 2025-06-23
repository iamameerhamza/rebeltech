<?php
$conn = new mysqli("localhost", "root", "", "rebeltech");

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$product_id = $_POST['product_id'];
$product_name = $_POST['product_name'];
$price = $_POST['price'];
$quantity = $_POST['quantity'];
$image_url = $_POST['image_url'];

$sql = "INSERT INTO cart (product_id, product_name, price, quantity, image_url)
        VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssdis", $product_id, $product_name, $price, $quantity, $image_url);
$stmt->execute();

$conn->close();

// Redirect to cart page
header("Location: cart.html");
exit();
?>
