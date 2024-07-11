<?php
function h($var) {
    if (is_array($var)) {
        return array_map('h', $var);
    } else {
        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    }
}

$dbServer = 'localhost';    // データベースサーバーのホスト名
$dbUser = 'yabuki-a';       // データベースユーザー名
$dbPass = 'pass';  // データベースパスワード
$dbName = 'mydb';           // データベース名

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

try {
    $db = new PDO($dsn, $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to the database successfully.";
} catch (PDOException $e) {
    echo "Can't connect to the database: " . h($e->getMessage());
    exit;
}

$sql = file_get_contents('mydb.sql');

try {
    $db->exec($sql);
    echo "Tables created successfully.";
} catch (PDOException $e) {
    echo "Error: " . h($e->getMessage());
}
?>




