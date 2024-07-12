<?php
# HTMLでのエスケープ処理をする関数（データベースとは無関係だが，ついでにここで定義しておく．）
function h($var) {
  if (is_array($var)) {
    return array_map('h', $var);
  } else {
    return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
  }
}

$dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
    $dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'root';
    $dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : '';
    $dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

echo "Connecting to database: " . h($dbName) . "<br>"; // デバッグ用に追加

try {
  $db = new PDO($dsn, $dbUser, $dbPass);
} catch (PDOException $e) {
  echo "Can't connect to the database: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
  exit();
}
?>



