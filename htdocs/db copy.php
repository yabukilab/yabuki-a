<?php

// HTMLエスケープ関数
function h($var) {
  if (is_array($var)) {
    return array_map('h', $var);
  } else {
    return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
  }
}

// データベース接続情報
$dbServer = '127.0.0.1';
$dbUser   = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser';
$dbPass   = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName   = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'yabukia';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

try {
  $pdo = new PDO($dsn, $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]);
} catch (PDOException $e) {
  exit("データベース接続エラー: " . h($e->getMessage()));
}

try {
  $pdo = new PDO($dsn, $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
  ]);

  // デバッグ用: 接続データベース名を表示
  var_dump($pdo->query("SELECT DATABASE()")->fetchColumn());

  // デバッグ用: テーブル一覧を表示
  var_dump($pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN));

  exit; // 一旦ここで止めてデバッグします
} catch (PDOException $e) {
  exit("データベース接続エラー: " . h($e->getMessage()));
}

echo "User: $dbUser\n";
echo "Pass: $dbPass\n";
echo "DB:   $dbName\n";

