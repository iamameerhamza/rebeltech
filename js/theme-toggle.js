// theme-toggle.js

document.addEventListener("DOMContentLoaded", function () {
  const toggleBtn = document.getElementById("toggleTheme");
  const root = document.documentElement;

  // Load saved theme from localStorage
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme === "dark") {
    root.setAttribute("data-theme", "dark");
    toggleBtn.textContent = "☀️";
  }

  // Toggle between light and dark
  toggleBtn.addEventListener("click", () => {
    const isDark = root.getAttribute("data-theme") === "dark";

    if (isDark) {
      root.removeAttribute("data-theme");
      toggleBtn.textContent = "🌙";
      localStorage.setItem("theme", "light");
    } else {
      root.setAttribute("data-theme", "dark");
      toggleBtn.textContent = "☀️";
      localStorage.setItem("theme", "dark");
    }
  });
});
