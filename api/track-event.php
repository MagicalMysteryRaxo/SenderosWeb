<?php
header("Content-Type: application/json");

require_once "db.php";

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $data = json_decode(file_get_contents("php://input"), true);

    $stmt = $pdo->prepare("
        INSERT INTO site_events (
            visitor_id,
            session_id,
            event_type,
            page_url,
            page_title,
            referrer,
            button_text,
            button_location,
            utm_source,
            utm_medium,
            utm_campaign,
            device_type,
            screen_width,
            language_code
        ) VALUES (
            :visitor_id,
            :session_id,
            :event_type,
            :page_url,
            :page_title,
            :referrer,
            :button_text,
            :button_location,
            :utm_source,
            :utm_medium,
            :utm_campaign,
            :device_type,
            :screen_width,
            :language_code
        )
    ");

    $stmt->execute([
        ":visitor_id" => $data["visitorID"] ?? null,
        ":session_id" => $data["sessionID"] ?? null,
        ":event_type" => $data["eventType"] ?? null,
        ":page_url" => $data["pageURL"] ?? null,
        ":page_title" => $data["pageTitle"] ?? null,
        ":referrer" => $data["referrer"] ?? null,
        ":button_text" => $data["buttonText"] ?? null,
        ":button_location" => $data["buttonLocation"] ?? null,
        ":utm_source" => $data["utmSource"] ?? null,
        ":utm_medium" => $data["utmMedium"] ?? null,
        ":utm_campaign" => $data["utmCampaign"] ?? null,
        ":device_type" => $data["deviceType"] ?? null,
        ":screen_width" => $data["screenWidth"] ?? null,
        ":language_code" => $data["language"] ?? null
    ]);

    echo json_encode(["success" => true]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false]);
}