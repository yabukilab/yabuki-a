<?php
session_start();

function h($var) {
    if (is_array($var)) {
        return array_map('h', $var);
    } else {
        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    }
}

$dbServer = isset($_ENV['MYSQL_SERVER']) ? $_ENV['MYSQL_SERVER'] : '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER']) ? $_SERVER['MYSQL_USER'] : 'testuser';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName = isset($_SERVER['MYSQL_DB']) ? $_SERVER['MYSQL_DB'] : 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

try {
    $db = new PDO($dsn, $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Can't connect to the database: " . h($e->getMessage());
    exit();
}

// ログインチェック
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$books = []; // 初期化

// ここにユーザーが登録した本を表示するコードを記述します
try {
    $stmt = $db->prepare("SELECT title, author, publisher FROM books WHERE username = :username ORDER BY title ASC");
    $stmt->execute([':username' => $_SESSION['username']]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . h($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h2>Welcome, <?php echo h($_SESSION['username']); ?></h2>

    <h3>Your Books</h3>
    <ul>
        <?php foreach ($books as $book): ?>
            <li><?php echo h($book['title']); ?> by <?php echo h($book['author']); ?>, published by <?php echo h($book['publisher']); ?></li>
        <?php endforeach; ?>
    </ul>

    <a href="add_book.html">Add a Book</a>
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>


