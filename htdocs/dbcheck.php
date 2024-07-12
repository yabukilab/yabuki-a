<?php
// HTMLでのエスケープ処理をする関数を定義
function h($var) {
  if (is_array($var)) {
    return array_map('h', $var);
  } else {
    return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
  }
}

// 環境変数の値を表示
echo "MYSQL_SERVER: " . h($_ENV['MYSQL_SERVER']) . "<br>";
echo "MYSQL_USER: " . h($_SERVER['MYSQL_USER']) . "<br>";
echo "MYSQL_PASSWORD: " . h($_SERVER['MYSQL_PASSWORD']) . "<br>";
echo "MYSQL_DB: " . h($_SERVER['MYSQL_DB']) . "<br>";
?>
