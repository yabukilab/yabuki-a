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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $user_id = $_SESSION['user_id'];

    try {
        $stmt = $db->prepare("INSERT INTO books (title, author, publisher, user_id) VALUES (:title, :author, :publisher, :user_id)");
        $stmt->execute([
            ':title' => $title,
            ':author' => $author,
            ':publisher' => $publisher,
            ':user_id' => $user_id
        ]);
        header("Location: home.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . h($e->getMessage());
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Book</title>
</head>
<body>
    <form action="add_book.php" method="post">
        <label for="title">Title:</label>
        <input type="text" id="title" name="title" required>
        <label for="author">Author:</label>
        <input type="text" id="author" name="author" required>
        <label for="publisher">Publisher:</label>
        <input type="text" id="publisher" name="publisher" required>
        <button type="submit">Add Book</button>
    </form>
    <a href="home.php">Back to Home</a>
</body>
</html>


