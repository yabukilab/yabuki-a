<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $author = $_POST['author'] ?? '';
    $publisher = $_POST['publisher'] ?? '';

    if (!empty($title) && !empty($author) && !empty($publisher)) {
        // ログイン中のユーザIDを取得
        $user_id = $_SESSION['user_id'];

        try {
            $stmt = $db->prepare("INSERT INTO books (user_id, title, author, publisher) VALUES (:user_id, :title, :author, :publisher)");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':publisher', $publisher);
            $stmt->execute();

            echo "書籍が追加されました。";
            echo "<p><a href='home.php'>ホームへ戻る。</a></p>";
        } catch (PDOException $e) {
            echo "Error: " . h($e->getMessage());
        }
    } else {
        echo "タイトル、著者、出版社は空欄にできません。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>書籍追加</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header"></div>
    <h1>書籍追加画面</h1>
    <form method="POST" action="add_book.php">
        <label for="title">タイトル:</label>
        <input type="text" id="title" name="title" required>
        <br>
        <label for="author">著者:</label>
        <input type="text" id="author" name="author" required>
        <br>
        <label for="publisher">出版社:</label>
        <input type="text" id="publisher" name="publisher" required>
        <br>
        <input type="submit" value="書籍追加" class="btn btn--orange">

    </form>
</body>
</html>