<?php

# HTMLでのエスケープ処理をする関数（データベースとは無関係だが，ついでにここで定義しておく．）
function h($var) {
  if (is_array($var)) {
    return array_map('h', $var);
  } else {
    return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
  }
}

$dbServer = '127.0.0.1';  // データベースサーバのホスト名
$dbUser = 'testuser';      // データベースユーザ名
$dbPass = 'pass';          // データベースパスワード
$dbName = 'mydb';          // データベース名

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

echo "Connecting to database: " . h($dbName); // デバッグ用に追加

try {
  $db = new PDO($dsn, $dbUser, $dbPass);
  # プリペアドステートメントのエミュレーションを無効にする．
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  # エラー→例外
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Can't connect to the database: " . h($e->getMessage());
}
?>



