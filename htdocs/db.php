<?php
function h($var) {
    return is_array($var) ? array_map('h', $var) : htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
}

$dbServer = '127.0.0.1';
$dbUser = 'testuser';
$dbPass = 'pass';
$dbName = 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

try {
    $db = new PDO($dsn, $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // テーブル作成
    $db->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        );
    ");

    $db->exec("
        CREATE TABLE IF NOT EXISTS books (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            author VARCHAR(255) NOT NULL,
            publisher VARCHAR(255) NOT NULL,
            user_id INT,
            FOREIGN KEY (user_id) REFERENCES users(id)
        );
    ");

    echo "Tables created successfully.";
} catch (PDOException $e) {
    echo "Can't connect to the database: " . h($e->getMessage());
    exit();
}
?>
