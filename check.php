<?php
require 'config.php';

$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$username = DB_USER;
$password = DB_PASS;

try {
    $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    echo 'Can't connect to the database: ' . $e->getMessage();
    exit;
}

// データベース接続が成功した場合のみ以下のコードが実行される
$stmt = $db->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
?>