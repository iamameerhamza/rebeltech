<!DOCTYPE html>
<html lang="en" data-theme="">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products | Rebel Tech</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <!-- Header -->
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
        <button id="toggleTheme" aria-label="Toggle Theme">🌙</button>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main>
    <section class="hero">
      <div class="hero-text">
        <h2>Welcome to Rebel Tech –<br>The Future of Electronics</h2>
        <p class="highlight">Best Deals | Fast Delivery | Trusted Reviews</p>
        <p class="description">
          Explore our wide range of cutting–edge electronics at unbeatable prices. We offer the latest gadgets with exceptional customer service.
        </p>
        <a href="products.php" class="cta-btn">Shop Now</a>
      </div>
      <img src="https://i.pinimg.com/736x/30/88/d3/3088d3d597da816fe9862cd40cbaf1ce.jpg" alt="Laptop" class="hero-image">
    </section>

    <!-- Static Thumbnails -->
    <section class="product-thumbnails">
      <div class="product-card">
        <img src="https://i.ibb.co/vdkQFxM/laptop1.png" alt="Gaming Laptop">
        <p>Gaming Laptop</p>
      </div>
      <div class="product-card">
        <img src="https://i.ibb.co/BKmMbsL/laptop.png" alt="Ultrabook Pro">
        <p>Ultrabook Pro</p>
      </div>
      <div class="product-card">
        <img src="https://i.ibb.co/x3jtzx7/mouse.png" alt="Mouse">
        <p>Wireless Mouse</p>
      </div>
      <div class="product-card">
        <img src="https://i.ibb.co/hFmKPH4/pad.png" alt="Gaming Pad">
        <p>Gaming Controller</p>
      </div>
    </section>

    <!-- Dynamic Products Section -->
    <section class="dynamic-products">
      <h2 style="text-align:center;">Explore More Products</h2>
      <div id="productContainer" class="product-grid">
        <!-- JS will inject product cards here -->
      </div>
    </section>
  </main>

  <!-- Footer -->
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

  <!-- Dynamic Product Load Script -->
  <script>
  document.addEventListener('DOMContentLoaded', () => {
      const container = document.getElementById('productContainer');

      fetch('get-product.php')
          .then(res => {
              if (!res.ok) throw new Error("HTTP error! Status: " + res.status);
              return res.json();
          })
          .then(data => {
              if (data.status !== 'success') {
                  container.innerHTML = '<p class="no-products">Failed to load products.</p>';
                  return;
              }

              if (!data.data || data.data.length === 0) {
                  container.innerHTML = '<p class="no-products">No products available at the moment.</p>';
                  return;
              }

              container.innerHTML = '';
              data.data.forEach(product => {
                  const card = document.createElement('div');
                  card.className = 'product-item';
                  card.innerHTML = `
                      <img src="${product.image_url || 'images/default-product.jpg'}"
                           alt="${product.name}"
                           onerror="this.onerror=null;this.src='images/default-product.jpg';">
                      <h3>${product.name}</h3>
                      <p>${product.description}</p>
                      <div class="product-price">Price: $${parseFloat(product.price).toFixed(2)}</div>
                      <div class="product-actions">
                          <a href="cart.php?action=add&id=${product.id}" class="add-to-cart">Add to Cart</a>
                          <a href="details.php?id=${product.id}" class="details-btn">Details</a>
                      </div>
                  `;
                  container.appendChild(card);
              });
          })
          .catch(err => {
              console.error('Fetch error:', err);
              container.innerHTML = `<p class="error-message">Error loading products: ${err.message}</p>`;
          });
  });
  </script>

  <!-- Theme toggle script -->
  <script src="js/theme-toggle.js"></script>

</body>
</html>
