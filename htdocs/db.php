<?php
function h($var) {
    if (is_array($var)) {
        return array_map('h', $var);
    } else {
        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    }
}

$dbServer = 'localhost';  // ホスト名を確認してください
$dbUser = 'testuser';     // ユーザー名を確認してください
$dbPass = 'pass';         // パスワードを確認してください
$dbName = 'mydb';         // データベース名を確認してください

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

try {
    $db = new PDO($dsn, $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Can't connect to the database: " . h($e->getMessage());
    exit;
}
?>

