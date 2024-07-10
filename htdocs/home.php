<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $db->prepare("SELECT id, title, author, publisher FROM books WHERE user_id = :user_id");
$stmt->execute([':user_id' => $user_id]);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1>My Books</h1>
<ul>
    <?php foreach ($books as $book): ?>
        <li><?php echo h($book['title']) . " by " . h($book['author']) . ", published by " . h($book['publisher']); ?></li>
    <?php endforeach; ?>
</ul>
<a href="add_book.php">Add a new book</a>
<a href="logout.php">Logout</a>

