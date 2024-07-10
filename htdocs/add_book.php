<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $user_id = $_SESSION['user_id'];

    $stmt = $db->prepare("INSERT INTO books (title, author, publisher, user_id) VALUES (:title, :author, :publisher, :user_id)");
    $stmt->execute([':title' => $title, ':author' => $author, ':publisher' => $publisher, ':user_id' => $user_id]);

    echo "Book successfully added.";
}
?>
<form method="post" action="add_book.php">
    Title: <input type="text" name="title">
    Author: <input type="text" name="author">
    Publisher: <input type="text" name="publisher">
    <input type="submit" value="Add Book">
</form>


