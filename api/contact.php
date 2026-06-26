<?php



if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../contact.html");
    exit;
}

$name = htmlspecialchars(trim($_POST["name"] ?? ""));
$email = filter_var(trim($_POST["email"] ?? ""), FILTER_VALIDATE_EMAIL);
$message = htmlspecialchars(trim($_POST["message"] ?? ""));

if (!$name || !$email || !$message) {
    echo "Please fill out all fields correctly.";
    exit;
}

$to = "contact@newchapterinitiative.org"; 
$subject = "New message to New Chapter";

$body = "Name: $name\n";
$body .= "Email: $email\n\n";
$body .= "Message:\n$message\n";

$headers = "From: connect@newchapterinitiative.org\r\n";
$headers .= "Reply-To: $email\r\n";

$result = mail($to, $subject, $body, $headers);

if ($result) {
    // Redirect back with a success flag
    header("Location: ../contact.html?status=success");
    exit;
} else {
    // Redirect back with an error flag
    header("Location: ../contact.html?status=error");
    exit;
}