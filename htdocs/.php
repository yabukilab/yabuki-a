<?php
$dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
    $dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'root';
    $dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : '';
    $dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

echo "Connecting to database: " . h($dbName); // デバッグ用に追加

try {
  $db = new PDO($dsn, $dbUser, $dbPass);
  echo "Connected successfully to database: " . h($dbName); // デバッグ用に追加
} catch (PDOException $e) {
  echo "Can't connect to the database: " . h($e->getMessage());
}
?>
