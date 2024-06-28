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

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';

$query = "SELECT * FROM books WHERE title LIKE :search OR author LIKE :search OR publisher LIKE :search";

if ($sort === 'title') {
    $query .= " ORDER BY title ASC";
} elseif ($sort === 'author') {
    $query .= " ORDER BY author ASC";
} elseif ($sort === 'publisher') {
    $query .= " ORDER BY publisher ASC";
}

try {
    $stmt = $db->prepare($query);
    $stmt->bindValue(':search', "%$search%");
    $stmt->execute();
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h2>Welcome, <?php echo h($_SESSION['username']); ?>!</h2>
    <form method="GET" action="home.php">
        <label for="search">Search:</label>
        <input type="text" id="search" name="search" value="<?php echo h($search); ?>">
        <label for="sort">Sort by:</label>
        <select id="sort" name="sort">
            <option value="">Select</option>
            <option value="title" <?php if ($sort === 'title') echo 'selected'; ?>>Title</option>
            <option value="author" <?php if ($sort === 'author') echo 'selected'; ?>>Author</option>
            <option value="publisher" <?php if ($sort === 'publisher') echo 'selected'; ?>>Publisher</option>
        </select>
        <input type="submit" value="Search">
    </form>
    <h2>Book List</h2>
    <ul>
        <?php foreach ($books as $book): ?>
            <li>
                <?php echo h($book['title']); ?> by <?php echo h($book['author']); ?>, published by <?php echo h($book['publisher']); ?>
                <a href="delete_book.php?id=<?php echo h($book['id']); ?>">Delete</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <p><a href="add_book.php">Add a new book</a></p>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>
