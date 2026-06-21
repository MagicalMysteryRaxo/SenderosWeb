const newsletterForm = document.getElementById("newsletter-form");
const newsletterMessage = document.getElementById("newsletter-message");

newsletterForm.addEventListener("submit", async (e) => {
  e.preventDefault();

  const firstName = document.getElementById("first-name").value.trim();
  const lastName = document.getElementById("last-name").value.trim();
  const email = document.getElementById("email").value.trim();

  newsletterMessage.textContent = "Signing you up...";

  try {
    const response = await fetch("api/newsletter-subscribe.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        firstName,
        lastName,
        email,
        sourcePage: window.location.pathname,
      }),
    });

    const result = await response.json();

    if (result.success) {
      newsletterMessage.textContent = "Thank you for joining!";
      newsletterForm.reset();
    } else {
      newsletterMessage.textContent =
        result.message || "Something went wrong. Please try again.";
    }
  } catch (error) {
    newsletterMessage.textContent = "Connection error. Please try again.";
  }
});
