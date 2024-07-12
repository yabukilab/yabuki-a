<?php
require 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$search = $_GET['search'] ?? '';
$sort = $_GET['sort'] ?? '';

$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM books WHERE user_id = :user_id";

if (!empty($search)) {
    $query .= " AND (title LIKE :search OR author LIKE :search2 OR publisher LIKE :search3)";
}

if ($sort === 'title') {
    $query .= " ORDER BY title ASC";
} elseif ($sort === 'author') {
    $query .= " ORDER BY author ASC";
} elseif ($sort === 'publisher') {
    $query .= " ORDER BY publisher ASC";
}

try {
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT); //変更点
    
    if (!empty($search)) {
        $searchWildcard = "%$search%";
        $stmt->bindValue(':search', $searchWildcard, PDO::PARAM_STR);
        $stmt->bindValue(':search2', $searchWildcard, PDO::PARAM_STR);
        $stmt->bindValue(':search3', $searchWildcard, PDO::PARAM_STR);
    }
    
    $stmt->execute();
    $books = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');//へんこうてん
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ホーム画面</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header"></div>
    <h1>ようこそ、 <?php echo h($_SESSION['username']); ?>さん</h1>
    <form method="GET" action="home.php">
        <label for="search">検索:</label>
        <input type="text" id="search" name="search" value="<?php echo h($search); ?>">
        <label for="sort">並び替え</label>
        <select id="sort" name="sort">
            <option value="">選択</option>
            <option value="title" <?php if ($sort === 'title') echo 'selected'; ?>>タイトル</option>
            <option value="author" <?php if ($sort === 'author') echo 'selected'; ?>>著者</option>
            <option value="publisher" <?php if ($sort === 'publisher') echo 'selected'; ?>>出版社</option>
        </select>
        <input type="submit" value="検索" class="btn btn--orange">
    </form>
    <h2>書籍一覧</h2>
    <ul>
        <?php foreach ($books as $book): ?>
            <li>
                <?php echo h($book['title']); ?> 著者: <?php echo h($book['author']); ?>, 出版社: <?php echo h($book['publisher']); ?>
                <a href="delete_book.php?id=<?php echo h($book['book_id']); ?>">書籍の削除</a>
            </li>
        <?php endforeach; ?>
    </ul>
    <p><a href="add_book.php">書籍の追加</a></p>
    <p><a href="logout.php">ログアウト</a></p>
</body>
</html>
