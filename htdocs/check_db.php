<?php
require 'db.php';  // あなたの上記コードが含まれている前提

echo "<h3>現在の接続情報</h3>";
echo "<ul>";
echo "<li>ホスト: " . h($dbServer) . "</li>";
echo "<li>ユーザー名: " . h($dbUser) . "</li>";
echo "<li>データベース名: " . h($dbName) . "</li>";
echo "</ul>";
?>
