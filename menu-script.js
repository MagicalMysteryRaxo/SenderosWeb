fetch("menu.html")
  .then((response) => response.text())
  .then((html) => {
    document.getElementById("navbar-container").innerHTML = html;

    const hamburger = document.querySelector(".hamburger");
    const menu = document.querySelector(".main-menu");

    if (hamburger && menu) {
      hamburger.addEventListener("click", () => {
        menu.classList.toggle("active");
      });
    }
  })
  .catch((error) => console.error("Failed to load menu:", error));
