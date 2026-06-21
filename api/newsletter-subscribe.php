<?php
header("Content-Type: application/json");

require_once "db.php";

// Quitamos el var_dump($username) y el exit; para que el código pueda correr.

try {
    // Ahora $host, $database, $user y $password vienen limpitas desde db.php
    $pdo = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8mb4",
        $user,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $data = json_decode(file_get_contents("php://input"), true);

    $firstName  = trim($data["firstName"] ?? "");
    $lastName   = trim($data["lastName"] ?? "");
    $email      = trim($data["email"] ?? "");
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
        (email, first_name, last_name, source_page, status) 
        VALUES 
        (:email, :first_name, :last_name, :source_page, 'active')
    ");

    $stmt->execute([
        ":email"       => $email,
        ":first_name"  => $firstName,
        ":last_name"   => $lastName,
        ":source_page" => $sourcePage
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Thank you for joining!"
    ]);

} catch (PDOException $e) {
    // Si el correo ya existe (Clave duplicada en SQL)
    if ($e->getCode() == 23000) {
        echo json_encode([
            "success" => false,
            "message" => "This email is already subscribed."
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Database error: " . $e->getMessage()
        ]);
    }
}
?>