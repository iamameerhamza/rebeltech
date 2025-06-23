<?php
// Connect to DB
$conn = new mysqli("localhost", "root", "", "rebeltech");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $hashedPassword);
        $stmt->execute();
        $stmt->close();

        header("Location: login.php?success=1");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | Rebel Tech</title>
  <link rel="stylesheet" href="style.css">
  <style>
.auth-page {
  background-color: #1e1e1e;
  min-height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px 20px;
}

.auth-container {
  background-color: #2b2b2b;
  padding: 40px 30px;
  border-radius: 12px;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
  color: #f1f1f1;
}

.auth-header h1 {
  font-size: 28px;
  margin-bottom: 10px;
  color: #ffffff;
}

.auth-header p {
  color: #aaa;
  margin-bottom: 30px;
  font-size: 15px;
}

.auth-form .form-group {
  margin-bottom: 20px;
}

.auth-form label {
  display: block;
  margin-bottom: 8px;
  font-size: 14px;
  color: #ddd;
}

.input-with-icon input {
  width: 100%;
  padding: 12px 14px;
  border: 1px solid #444;
  border-radius: 8px;
  background-color: #1e1e1e;
  color: #fff;
  font-size: 15px;
  transition: border 0.3s ease;
}

.input-with-icon input:focus {
  border-color: #007bff;
  outline: none;
}

.auth-btn {
  width: 100%;
  padding: 12px;
  background-color: #007bff;
  border: none;
  border-radius: 8px;
  color: white;
  font-size: 16px;
  font-weight: bold;
  cursor: pointer;
  transition: background-color 0.3s ease;
  margin-top: 10px;
}

.auth-btn:hover {
  background-color: #0056b3;
}

.auth-footer {
  margin-top: 25px;
  text-align: center;
  font-size: 14px;
  color: #ccc;
}

.auth-footer a {
  color: #00ccff;
  text-decoration: none;
  transition: color 0.3s ease;
}

.auth-footer a:hover {
  color: #00aacc;
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
        <a href="index.php" >Home</a>
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
  <main class="auth-page">
    <div class="auth-container">
      <div class="auth-header">
        <h1>Create Account</h1>
        <p>Sign up to access Rebel Tech</p>
      </div>

      <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>

      <form class="auth-form" method="POST">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" name="name" id="name" required>
        </div>

        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" name="email" id="email" required>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" name="password" id="password" required>
        </div>

        <div class="form-group">
          <label for="confirm_password">Confirm Password</label>
          <input type="password" name="confirm_password" id="confirm_password" required>
        </div>

        <button type="submit" class="auth-btn">Register</button>

        <div class="auth-footer">
          <p>Already have an account? <a href="login.php">Sign in</a></p>
        </div>
      </form>
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

