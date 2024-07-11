<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

function h($var) {
    if (is_array($var)) {
        return array_map('h', $var);
    } else {
        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    }
}

$dbServer = 'localhost';
$dbUser = 'testuser';
$dbPass = 'pass';
$dbName = 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

try {
    $db = new PDO($dsn, $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Can't connect to the database: " . h($e->getMessage());
    exit;
}

try {
    $stmt = $db->prepare("SELECT book_id, title, author, publisher FROM books WHERE username = ?");
    $stmt->execute([$_SESSION['username']]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . h($e->getMessage());
    exit;
}
?>

<!-- HTMLで本のリストを表示 -->
<h1>Your Books</h1>
<ul>
    <?php foreach ($books as $book): ?>
        <li><?php echo h($book['title']) . " by " . h($book['author']) . ", published by " . h($book['publisher']); ?></li>
    <?php endforeach; ?>
</ul>

<!-- 本の追加フォーム -->
<h2>Add a new book</h2>
<form method="post" action="add_book.php">
    Title: <input type="text" name="title" required><br>
    Author: <input type="text" name="author" required><br>
    Publisher: <input type="text" name="publisher" required><br>
    <input type="submit" value="Add Book">
</form>

<!-- ログアウトボタン -->
<form method="post" action="logout.php">
    <input type="submit" value="Logout">
</form>
