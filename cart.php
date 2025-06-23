<?php
session_start();

// DB connection
$conn = new mysqli("localhost", "root", "", "rebeltech");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle add to cart action
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (!isset($_SESSION['cart'][$id]) || !is_numeric($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] = 1;
    } else {
        $_SESSION['cart'][$id]++;
    }

    $quantity = $_SESSION['cart'][$id];
    $stmt = $conn->prepare("INSERT INTO cart_items (product_id, quantity) VALUES (?, ?) ON DUPLICATE KEY UPDATE quantity = ?");
    $stmt->bind_param("iii", $id, $quantity, $quantity);
    $stmt->execute();
    $stmt->close();

    header("Location: cart.php");
    exit();
}

// Handle remove from cart
if (isset($_GET['action']) && $_GET['action'] == 'remove' && isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];
    unset($_SESSION['cart'][$id]);

    $stmt = $conn->prepare("DELETE FROM cart_items WHERE product_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: cart.php");
    exit();
}

// Get products from cart
$cartItems = $_SESSION['cart'] ?? [];
$products = [];

if (!empty($cartItems)) {
    $ids = implode(',', array_map('intval', array_keys($cartItems)));
    $sql = "SELECT * FROM products WHERE id IN ($ids)";
    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        $row['quantity'] = (int)$cartItems[$row['id']];
        $row['subtotal'] = (float)$row['price'] * $row['quantity'];

        $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE product_id = ?");
        $stmt->bind_param("ii", $row['quantity'], $row['id']);
        $stmt->execute();
        $stmt->close();

        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Your Cart | Rebel Tech</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    /* Cart Page */
.cart-page {
  padding: 60px 20px;
  background-color: #1e1e1e;
  color: #f1f1f1;
  min-height: 100vh;
}

.cart-page h1 {
  font-size: 32px;
  margin-bottom: 30px;
  color: #fff;
}

.cart-table {
  width: 100%;
  border-collapse: collapse;
  background-color: #2b2b2b;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 0 15px rgba(0, 0, 0, 0.5);
  margin-bottom: 30px;
}

.cart-table thead {
  background-color: #333;
}

.cart-table th,
.cart-table td {
  padding: 16px 12px;
  text-align: center;
  border-bottom: 1px solid #444;
}

.cart-table th {
  color: #ddd;
  font-size: 16px;
  font-weight: 600;
}

.cart-table td {
  color: #ccc;
  font-size: 15px;
}

.cart-table tr:last-child td {
  border-bottom: none;
}

.remove-btn {
  display: inline-block;
  padding: 6px 12px;
  color: #fff;
  background-color: #dc3545;
  border-radius: 6px;
  text-decoration: none;
  transition: background-color 0.3s ease;
  font-size: 14px;
}

.remove-btn:hover {
  background-color: #c82333;
}

.cart-total {
  font-size: 18px;
  font-weight: bold;
  text-align: right;
  margin-bottom: 20px;
  color: #00cc88;
}

.cart-actions {
  text-align: right;
}

.checkout-btn {
  display: inline-block;
  padding: 12px 24px;
  background-color: #007bff;
  color: white;
  text-decoration: none;
  border-radius: 8px;
  font-weight: bold;
  transition: background-color 0.3s ease;
}

.checkout-btn:hover {
  background-color: #0056b3;
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

<main class="cart-page">
  <h1 style="text-align:center">Your Shopping Cart</h1>
  <?php if (empty($products)): ?>
    <p style="text-align:center">Your cart is empty.</p>
  <?php else: ?>
    <table class="cart-table">
      <thead>
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Subtotal</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php $grandTotal = 0; ?>
        <?php foreach ($products as $product): ?>
          <?php $total = (float)$product['price'] * (int)$product['quantity']; ?>
          <?php $grandTotal += $total; ?>
          <tr>
            <td><?= htmlspecialchars($product['name']) ?></td>
            <td>$<?= number_format((float)$product['price'], 2) ?></td>
            <td><?= (int)$product['quantity'] ?></td>
            <td>$<?= number_format($total, 2) ?></td>
            <td><a class="remove-btn" href="cart.php?action=remove&id=<?= $product['id'] ?>">Remove</a></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <div class="cart-total">
      <strong>Grand Total:</strong> $<?= number_format($grandTotal, 2) ?>
    </div>
    <div class="cart-actions">
      <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
    </div>
  <?php endif; ?>
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
