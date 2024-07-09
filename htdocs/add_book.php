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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $username = $_SESSION['username'];

    try {
        $stmt = $db->prepare("INSERT INTO books (title, author, publisher, username) VALUES (:title, :author, :publisher, :username)");
        $stmt->execute([
            ':title' => $title,
            ':author' => $author,
            ':publisher' => $publisher,
            ':username' => $username
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Book</title>
</head>
<body>
    <form action="add_book.php" method="post">
        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" required>
        </div>
        <div>
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required>
        </div>
        <div>
            <label for="publisher">Publisher:</label>
            <input type="text" id="publisher" name="publisher" required>
        </div>
        <button type="submit">Add Book</button>
    </form>
    <a href="home.php">Back to Home</a>
</body>
</html>
