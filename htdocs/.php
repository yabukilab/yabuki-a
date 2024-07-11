<?php
$dbServer = '127.0.0.1';
$dbUser = 'testuser';
$dbPass = 'pass';
$dbName = 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

echo "Connecting to database: " . h($dbName); // デバッグ用に追加

try {
  $db = new PDO($dsn, $dbUser, $dbPass);
  echo "Connected successfully to database: " . h($dbName); // デバッグ用に追加
} catch (PDOException $e) {
  echo "Can't connect to the database: " . h($e->getMessage());
}
?>
