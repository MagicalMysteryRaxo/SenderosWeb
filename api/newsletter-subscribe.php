<?php
header("Content-Type: application/json");

require_once "db.php";



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

    addToMailerLite($email, $firstName, $lastName);
    
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


function addToMailerLite($email, $firstName, $lastName) {
    $apiKey = "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI0IiwianRpIjoiNGVkNTAxMDM4MjBjMWQ1NmZlNThjYzdkNDMwOWViMzI5YTliMGQ4YTJjNTY4YWZkNGQ3NjI5Zjc4NTgwMGZmM2U1OTIwMGY2YWY4NTRhMDgiLCJpYXQiOjE3ODI1MTE3NjguNjM0MzA5LCJuYmYiOjE3ODI1MTE3NjguNjM0MzExLCJleHAiOjQ5MzgxODUzNjguNjI3NDY5LCJzdWIiOiIyNDkwNjc2Iiwic2NvcGVzIjpbXX0.xV5TVxhAuPnuIDeXbM3fqvK8l-OfYjWCsAw_OJ2gzW7ICrBhsdzm4-h1pyt0q31gZlbsPm4AUUAC7qxoeT62puPoE-IzWJPKJKDpAbASDNHuS1g2BnlkoPQVKsXWbS0isi1g3UAq4jbi-iB9x5J41vgjW2q5WRg5LESkoTPDiEO5A6vJocZ7g5qKAaqKxa_MQLqdVe3kftY01NwEl-4yVg7Sf1JdAtUFV2msvDxvRNieEi2JL9d_2YoGeXJCZHbXrsxGBelVxrsNwK_9dc5BFN5DWE4AgrwagcLXE0IaUrJw_V3MfXT0RvbOSZWhMIEZlDqkp0ly1npdRsdBGe_oAcBDRAAk-KtwpTi_y72h2NjmZzLODN33GPO6wqWeV8TOkXj5mIb_5qrwDGzBBC-KA9Rkihr_RKFYiagyiKJFvVjyqrDZFJVYSizqwRZaRvbT6XNb_jiZ6eP1Bd40twbRmaeG8JEW7ubHNOyP5D8eYI3nvZqE8il_K9xnPa3XN0lQIjKwROzqMY0a3HxvrjPapym3wCceIQUQNB5Llk4n7oSinobk2sRm7qJt2hfdX1SzdlVIKAoEU5RUmkIWd2FWrumBY6gN_mnmDIe-lqa1BNVF9IpZrba_Okmna6lVU5YDDCDv764ZYxfg2lGNKQSeu3i1oNmzovIcfJamECRG8fE";
    $groupId = "191377625473615736";

    $data = [
        "email" => $email,
        "fields" => [
            "name" => $firstName,
            "last_name" => $lastName
        ],
        "groups" => [$groupId]
    ];

    $ch = curl_init("https://connect.mailerlite.com/api/subscribers");

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Authorization: Bearer " . $apiKey
        ],
        CURLOPT_POSTFIELDS => json_encode($data)
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}
?>