<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

function h($var) {
    return is_array($var) ? array_map('h', $var) : htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
}

$dbServer = '127.0.0.1';
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
    exit();
}

$user_id = $_SESSION['user_id'];

try {
    $stmt = $db->prepare("SELECT * FROM books WHERE user_id = :user_id ORDER BY title");
    $stmt->execute([':user_id' => $user_id]);
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . h($e->getMessage());
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
</head>
<body>
    <h1>Your Books</h1>
    <ul>
        <?php foreach ($books as $book): ?>
            <li><?php echo h($book['title']) . " by " . h($book['author']) . ", published by " . h($book['publisher']); ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="add_book.php">Add a Book</a>
    <br>
    <a href="logout.php">Logout</a>
</body>
</html>
