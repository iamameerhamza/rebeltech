<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Products | Rebel Tech</title>
  <link rel="stylesheet" href="style.css" />
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
        <button id="toggleTheme" aria-label="Toggle Theme">🌙</button>
      </div>
    </div>
  </header>

<main class="products-page">
  <h1>Our Products</h1>
  <div class="products-grid" id="productContainer">
    <!-- Products will be loaded dynamically -->
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


<!-- Search redirection -->
<script>
  document.querySelector('.search-box input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
      window.location.href = 'search.html?q=' + encodeURIComponent(this.value);
    }
  });
</script>

<!-- Dynamic Product Load Script -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const container = document.getElementById('productContainer');

    fetch('get-product.php')
        .then(res => res.json())
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

</body>
</html>
