<?php

require_once "db.php";

$sql = "
CREATE TABLE site_events (
  event_id BIGINT AUTO_INCREMENT PRIMARY KEY,
  visitor_id VARCHAR(100),
  session_id VARCHAR(100),
  event_type VARCHAR(50) NOT NULL,
  event_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

  page_url TEXT,
  page_title VARCHAR(255),
  referrer TEXT,

  button_text VARCHAR(150),
  button_location VARCHAR(150),

  utm_source VARCHAR(100),
  utm_medium VARCHAR(100),
  utm_campaign VARCHAR(100),

  device_type VARCHAR(50),
  screen_width INT,
  language_code VARCHAR(20)
);
";

if ($conn->query($sql) === TRUE) {
    echo "Database and tables created successfully!";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();

?>