const signupButtons = document.querySelectorAll(".newsletter-signup");
const popup = document.getElementById("newsletter-popup");
const closeButton = document.getElementById("close-newsletter");

signupButtons.forEach((button) => {
  button.addEventListener("click", (e) => {
    e.preventDefault();
    popup.classList.add("active");
  });
});

closeButton.addEventListener("click", () => {
  popup.classList.remove("active");
});
