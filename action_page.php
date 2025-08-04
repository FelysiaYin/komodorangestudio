<?php
// Only proceed on POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Sanitize & validate
    $raw = $_POST['email'] ?? '';
    $email = filter_var(trim($raw), FILTER_SANITIZE_EMAIL);

    if ( filter_var($email, FILTER_VALIDATE_EMAIL) ) {
        // 2a. Append to a plain-text list
        $file = __DIR__ . '/subscribers.txt';
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND | LOCK_EX);

        //  -- or, insert into a database with PDO:
        // $pdo = new PDO('mysql:host=localhost;dbname=mydb', 'user','pass');
        // $stmt = $pdo->prepare('INSERT INTO subscribers (email) VALUES (:email)');
        // $stmt->execute(['email' => $email]);

        echo "✅ Thanks for subscribing, " . htmlspecialchars($email);
    } else {
        // Invalid email
        http_response_code(400);
        echo "❌ That doesn’t look like a valid email address.";
    }
} else {
    // Prevent direct GET access
    header('Location: subscribe.html');
    exit;
}
