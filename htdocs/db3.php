<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
    $dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'root';
    $dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : '';
    $dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';
    $user = $_POST['username'];
    $pass = $_POST['password'];

    $dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
}
try {
    // PDOオブジェクトの作成
    $db = new PDO($dsn, $dbUser, $dbPass);
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

} catch (PDOException $e) {
    echo "Can't connect to the database: " . h($e->getMessage());
}
?>
