<?php
header("Content-Type: application/json");

$host = "localhost";
$dbname = "YOUR_DATABASE_NAME";
$username = "YOUR_DATABASE_USERNAME";
$password = "YOUR_DATABASE_PASSWORD";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $data = json_decode(file_get_contents("php://input"), true);

    $firstName = trim($data["firstName"] ?? "");
    $lastName = trim($data["lastName"] ?? "");
    $email = trim($data["email"] ?? "");
    $sourcePage = trim($data["sourcePage"] ?? "");

    if (empty($firstName) || empty($lastName) || empty($email)) {

        echo json_encode([
            "success" => false,
            "message" => "Please complete all fields."
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo json_encode([
            "success" => false,
            "message" => "Invalid email address."
        ]);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO newsletter_subscribers
        (
            email,
            first_name,
            last_name,
            source_page,
            status
        )
        VALUES
        (
            :email,
            :first_name,
            :last_name,
            :source_page,
            'active'
        )
    ");

    $stmt->execute([
        ":email" => $email,
        ":first_name" => $firstName,
        ":last_name" => $lastName,
        ":source_page" => $sourcePage
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Thank you for joining!"
    ]);

} catch (PDOException $e) {

    // Duplicate email
    if ($e->getCode() == 23000) {

        echo json_encode([
            "success" => false,
            "message" => "This email is already subscribed."
        ]);

    } else {

        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }
}