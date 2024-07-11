<?php
# HTMLでのエスケープ処理をする関数（データベースとは無関係だが，ついでにここで定義しておく．）
function h($var) {
  if (is_array($var)) {
    return array_map('h', $var);
  } else {
    return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
  }
}

$dbServer = '127.0.0.1';
$dbUser = 'testuser';
$dbPass = 'pass';
$dbName = 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

echo "Connecting to database: " . h($dbName) . "<br>"; // デバッグ用に追加

try {
  $db = new PDO($dsn, $dbUser, $dbPass);
  echo "Connected successfully to database: " . h($dbName) . "<br>"; // デバッグ用に追加
} catch (PDOException $e) {
  echo "Can't connect to the database: " . h($e->getMessage()) . "<br>";
}
?>



