<?php
// Connect to DB
$conn = new mysqli("localhost", "root", "", "rebeltech");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Initialize default values
$product = null;
$qty = isset($_GET['qty']) && is_numeric($_GET['qty']) ? (int)$_GET['qty'] : 1;
$total = 0;

// Fetch product if ID is set
if (isset($_GET['product_id']) && is_numeric($_GET['product_id'])) {
    $id = (int)$_GET['product_id'];
    $result = $conn->query("SELECT * FROM products WHERE id = $id");

    if ($result && $result->num_rows > 0) {
        $product = $result->fetch_assoc();
        $total = $product['price'] * $qty;
    }
}

// Handle form submission to save order
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['account_name']);
    $bank = $conn->real_escape_string($_POST['bank_name']);
    $acc = $conn->real_escape_string($_POST['account_number']);
    $iban = $conn->real_escape_string($_POST['iban']);
    $pid = isset($product['id']) ? (int)$product['id'] : 0;
    $pname = isset($product['name']) ? $conn->real_escape_string($product['name']) : '';
    $pprice = isset($product['price']) ? (float)$product['price'] : 0;

    $conn->query("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        product_id INT,
        product_name VARCHAR(255),
        quantity INT,
        total_price DECIMAL(10,2),
        account_name VARCHAR(255),
        bank_name VARCHAR(255),
        account_number VARCHAR(50),
        iban VARCHAR(50),
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    $stmt = $conn->prepare("INSERT INTO orders (product_id, product_name, quantity, total_price, account_name, bank_name, account_number, iban)
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $grand_total = $pprice * $qty * 1.1;
    $stmt->bind_param("isidssss", $pid, $pname, $qty, $grand_total, $name, $bank, $acc, $iban);
    $stmt->execute();
    $stmt->close();

    header("Location: thankyou.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Checkout | Rebel Tech</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    /* Checkout Page Styles */
.checkout-page {
  padding: 40px 20px;
  background-color: #1e1e1e; /* dark background */
  color: #fff;
  min-height: 100vh;
}

.checkout-container {
  max-width: 900px;
  margin: auto;
  background-color:rgb(0, 0, 0);
  border-radius: 12px;
  padding: 30px;
  box-shadow: 0 0 12px rgba(0, 0, 0, 0.5);
}

.checkout-steps {
  display: flex;
  justify-content: space-between;
  margin-bottom: 30px;
}

.step {
  flex: 1;
  text-align: center;
  padding: 10px 0;
  background-color: #3a3a3a;
  border-radius: 8px;
  font-weight: bold;
  color: #ccc;
}

.step.active {
  background-color: #007bff;
  color: white;
}

.checkout-form h2 {
  margin-bottom: 20px;
  font-size: 24px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-size: 16px;
  margin-bottom: 8px;
  color: #ddd;
}

.form-group input {
  width: 100%;
  padding: 10px;
  border-radius: 8px;
  border: 1px solid #444;
  background-color:rgb(255, 255, 255);
  color: #fff;
  font-size: 16px;
  transition: border 0.3s ease;
}

.form-group input:focus {
  border-color: #007bff;
  outline: none;
}

.continue-btn {
  width: 100%;
  padding: 12px;
  border: none;
  background-color: #007bff;
  color: white;
  font-size: 18px;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s ease;
}

.continue-btn:hover {
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
    <div class="secure-checkout">🔒 Secure Checkout</div>
  </header>

  <main class="checkout-page">
    <div class="checkout-container">
      <div class="checkout-steps">
        <div class="step active">1. Bank Details</div>
        <div class="step">2. Finish</div>
      </div>

      <div class="checkout-grid">
        <div class="checkout-form">
          <h2>Bank Account Information</h2>
          <form method="POST">
            <div class="form-group">
              <label for="account-name">Account Holder Name</label>
              <input type="text" name="account_name" id="account-name" required />
            </div>

            <div class="form-group">
              <label for="bank-name">Bank Name</label>
              <input type="text" name="bank_name" id="bank-name" required />
            </div>

            <div class="form-group">
              <label for="account-number">Bank Account Number</label>
              <input type="text" name="account_number" id="account-number" required />
            </div>

            <div class="form-group">
              <label for="iban">IBAN</label>
              <input type="text" name="iban" id="iban" required />
            </div>

            <button type="submit" class="continue-btn">Finish</button>
          </form>
        </div>

        <div class="order-summary">
          <h2>Order Summary</h2>
          <div class="summary-items">
            <?php if ($product): ?>
              <div class="summary-item">
                <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" onerror="this.src='images/default-product.jpg';">
                <div class="item-details">
                  <h4><?= htmlspecialchars($product['name']) ?></h4>
                  <p>Qty: <?= $qty ?></p>
                </div>
                <div class="item-price">Rs <?= number_format($product['price'], 2) ?></div>
              </div>
            <?php else: ?>
              <p>No product selected for checkout.</p>
            <?php endif; ?>
          </div>

          <?php if ($product): ?>
          <div class="summary-totals">
            <div class="total-row">
              <span>Subtotal</span>
              <span>Rs <?= number_format($product['price'] * $qty, 2) ?></span>
            </div>
            <div class="total-row">
              <span>Shipping</span>
              <span>FREE</span>
            </div>
            <div class="total-row">
              <span>Tax</span>
              <span>Rs <?= number_format(($product['price'] * $qty) * 0.1, 2) ?></span>
            </div>
            <div class="total-row grand-total">
              <span>Total</span>
              <span>Rs <?= number_format(($product['price'] * $qty) * 1.1, 2) ?></span>
            </div>
          </div>
          <?php endif; ?>

          <div class="secure-payment">
            <p>🔒 All bank transactions are secure and verified by your Pakistani bank</p>
          </div>
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