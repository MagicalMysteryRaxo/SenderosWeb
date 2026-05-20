fetch("menu.html")
  .then((response) => response.text())
  .then((html) => {
    document.getElementById("navbar-container").innerHTML = html;

    const hamburger = document.querySelector(".hamburger");
    const menu = document.querySelector(".main-menu");
    const overlay = document.querySelector(".menu-overlay");

    hamburger.addEventListener("click", () => {
      menu.classList.toggle("active");
      overlay.classList.toggle("active");
      hamburger.classList.toggle("active");
    });

    overlay.addEventListener("click", () => {
      menu.classList.remove("active");
      overlay.classList.remove("active");
      hamburger.classList.remove("active");
    });
  })
  .catch((error) => console.error("Failed to load menu:", error));
