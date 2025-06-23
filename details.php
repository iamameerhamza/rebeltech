
<?php
session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "rebeltech");
if ($conn->connect_error) {
    die("DB Connection failed: " . $conn->connect_error);
}

// Validate and fetch product ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID");
}

$id = (int) $_GET['id'];
$result = $conn->query("SELECT * FROM products WHERE id = $id");
if (!$result || $result->num_rows === 0) {
    die("Product not found");
}

$product = $result->fetch_assoc();

// Insert into details_log table (optional)
$conn->query("CREATE TABLE IF NOT EXISTS details_log (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    viewed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

$stmt = $conn->prepare("INSERT INTO details_log (product_id) VALUES (?)");
if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($product['name']) ?> | Rebel Tech</title>
  <link rel="stylesheet" href="style.css">
  <style>/* Product Details Page */
.product-details {
  padding: 60px 20px;
  background-color:rgb(255, 255, 255);
  color: #f1f1f1;
  min-height: 100vh;
}

.product-details-container {
  max-width: 1200px;
  margin: auto;
  display: flex;
  flex-wrap: wrap;
  gap: 40px;
  background-color: #2b2b2b;
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
}

.product-gallery {
  flex: 1;
  min-width: 300px;
  text-align: center;
}

.product-gallery img {
  max-width: 100%;
  border-radius: 12px;
  object-fit: cover;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
}

.product-info {
  flex: 1;
  min-width: 300px;
}

.product-info h1 {
  font-size: 32px;
  margin-bottom: 15px;
}

.product-price {
  font-size: 24px;
  color: #00cc88;
  margin-bottom: 15px;
}

.product-info p {
  line-height: 1.6;
  margin-bottom: 20px;
  color: #ddd;
}

.product-actions {
  display: flex;
  gap: 20px;
}

.product-actions a {
  display: inline-block;
  padding: 12px 20px;
  border-radius: 8px;
  text-decoration: none;
  font-weight: bold;
  transition: background 0.3s;
}

.product-actions a:first-child {
  background-color: #007bff;
  color: #fff;
}

.product-actions a:first-child:hover {
  background-color: #0056b3;
}

.product-actions a.buy-now {
  background-color: #28a745;
  color: #fff;
}

.product-actions a.buy-now:hover {
  background-color: #1e7e34;
}
</style>

</head>
<body>
<header>
    <div class="container header-container">
      <div class="left-section">
        <div class="logo">
          <div class="icon">RT</div>
          <span>REBELTECH</span>
        </div>
      </div>

      <nav>
        <a href="index.php" class="active">Home</a>
        <a href="products.php">Products</a>
        <a href="cart.php">Cart</a>
        <a href="login.php">Login</a>
      </nav>

      <!-- Theme Toggle Button -->
      <div class="theme-toggle">
        <button id="toggleTheme" aria-label="Toggle Theme"></button>
      </div>
    </div>
  </header>

<main class="product-details">
  <div class="product-details-container">
    <div class="product-gallery">
      <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
    </div>
    <div class="product-info">
      <h1><?= htmlspecialchars($product['name']) ?></h1>
      <p class="product-price">$<?= number_format($product['price'], 2) ?></p>
      <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
      <div class="product-actions">
        <a href="cart.php?action=add&id=<?= $product['id'] ?>">Add to Cart</a>
        <a href="checkout.php" class="buy-now">Buy Now</a>
      </div>
    </div>
  </div>
</main>

<footer>
    <div class="footer-container">
      <div class="footer-section about">
        <h3>Rebel Tech</h3>
        <p>Leading the future of electronics with cutting-edge technology, affordable prices, and reliable service.</p>
        <p><a href="about.php" style="color: #fff; text-decoration: underline;">About Us</a></p>
      </div>

      <div class="footer-section contact">
        <h4>Contact Info</h4>
        <p>Email: <a href="mailto:haro.khan777@gmail.com">haro.khan777@gmail.com</a></p>
        <p>Phone: <a href="tel:03110749511">0311-0749511</a></p>
      </div>

      <div class="footer-section links">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="#">Help Center</a></li>
          <li><a href="#">Reviews</a></li>
          <li><a href="#">Privacy Policy</a></li>
        </ul>
      </div>

      <div class="footer-section social">
        <h4>Follow Us</h4>
        <div class="social-icons">
          <a href="#"><img src="https://cdn-icons-png.flaticon.com/24/145/145802.png" alt="Facebook"></a>
          <a href="#"><img src="https://cdn-icons-png.flaticon.com/24/145/145812.png" alt="Instagram"></a>
          <a href="#"><img src="https://cdn-icons-png.flaticon.com/24/145/145807.png" alt="Twitter"></a>
          <a href="#"><img src="https://cdn-icons-png.flaticon.com/24/145/145808.png" alt="LinkedIn"></a>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <p>&copy; 2025 Rebel Tech. All rights reserved.</p>
    </div>
  </footer>

</body>
</html>
