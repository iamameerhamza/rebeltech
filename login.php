<?php
// Connect to DB
$conn = new mysqli("localhost", "root", "", "rebeltech");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Process login if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];

    // Check credentials
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            echo "<script>alert('Login successful!'); window.location.href = 'index.php';</script>";
            exit();
        } else {
            echo "<script>alert('Invalid password.');</script>";
        }
    } else {
        echo "<script>alert('User not found.');</script>";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Login | Rebel Tech</title>
  <link rel="stylesheet" href="style.css" />
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


  <main class="auth-page">
    <div class="auth-container">
      <div class="auth-header">
        <h1>Welcome Back</h1>
        <p>Sign in to access your Rebel Tech account</p>
      </div>
      
      <form class="auth-form" id="login-form" method="POST">
        <div class="form-group">
          <label for="email">Email Address</label>
          <div class="input-with-icon">
            <input type="email" id="email" name="email" placeholder="your@email.com" required>
          </div>
        </div>

        <div class="form-group">
          <label for="password">Password</label>
          <div class="input-with-icon">
            <input type="password" id="password" name="password" placeholder="••••••••" required>
          </div>
        </div>

        <button type="submit" class="auth-btn">
          <span class="btn-text">Sign In</span>
        </button>
      </form>

      <div class="auth-footer">
        <p>Don't have an account? <a href="register.php">Sign up</a></p>
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

   <script src="js/theme-toggle.js"></script>
</body>
</html>
