</php
require "db.php";

// PDOオブジェクトの作成
$db = new PDO($dsn, $dbUser, $dbPass);
# プリペアドステートメントのエミュレーションを無効にする．
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
# エラー→例外
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// テーブルのリストを取得
$tables = $db->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);

echo "<h2>Database: " . h($dbName) . "</h2>";

foreach ($tables as $table) {
    echo "<h3>Table: " . h($table) . "</h3>";

    // テーブル構造を取得
    $columns = $db->query("DESCRIBE $table")->fetchAll(PDO::FETCH_ASSOC);

    echo "<table border='1'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";

    foreach ($columns as $column) {
        echo "<tr>";
        echo "<td>" . h($column['Field']) . "</td>";
        echo "<td>" . h($column['Type']) . "</td>";
        echo "<td>" . h($column['Null']) . "</td>";
        echo "<td>" . h($column['Key']) . "</td>";
        echo "<td>" . h($column['Default']) . "</td>";
        echo "<td>" . h($column['Extra']) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>