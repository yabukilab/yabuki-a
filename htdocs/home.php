<?php
// データベース接続情報
$servername = "localhost";
$username = "testuser";
$password = "pass"; // データベースパスワード
$dbname = "mydb";

// データベース接続の確立
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続を確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 文字セットをUTF-8に設定
$conn->set_charset("utf8mb4");

// 検索クエリと並び替えの基準を取得
$search = $_GET['search'] ?? '';
$sort_by = $_GET['sort_by'] ?? 'title';

// SQLクエリを構築
$sql = "SELECT id, title, author, publisher FROM books WHERE title LIKE ? OR author LIKE ? OR publisher LIKE ? ORDER BY ";

// 並び替えの基準に基づいてSQLクエリを設定
switch ($sort_by) {
    case 'author':
        $sql .= "author";
        break;
    case 'publisher':
        $sql .= "publisher";
        break;
    case 'title':
    default:
        $sql .= "title";
        break;
}

// 準備されたステートメントを使用してクエリを実行
$stmt = $conn->prepare($sql);
$like_search = '%' . $search . '%';
$stmt->bind_param("sss", $like_search, $like_search, $like_search);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>
<body>
    <h2>Home Page</h2>
    
    <form method="GET" action="home.php">
        <input type="text" name="search" placeholder="Search by title, author, or publisher" value="<?php echo htmlspecialchars($search); ?>">
        <select name="sort_by">
            <option value="title" <?php echo $sort_by === 'title' ? 'selected' : ''; ?>>Title</option>
            <option value="author" <?php echo $sort_by === 'author' ? 'selected' : ''; ?>>Author</option>
            <option value="publisher" <?php echo $sort_by === 'publisher' ? 'selected' : ''; ?>>Publisher</option>
        </select>
        <input type="submit" value="Search and Sort">
    </form>
    
    <h3>Book List</h3>
    <ul>
        <?php
        if ($result->num_rows > 0) {
            // 出力データを各行ごとに表示
            while($row = $result->fetch_assoc()) {
                echo "<li>" . htmlspecialchars($row["title"]) . " by " . htmlspecialchars($row["author"]) . ", published by " . htmlspecialchars($row["publisher"]);
                echo " <a href='delete_book.php?id=" . $row["id"] . "'>Delete</a></li>";
            }
        } else {
            echo "No books found.";
        }
        $conn->close();
        ?>
    </ul>
    <p><a href="add_book.html">Add a new book</a></p>
</body>
</html>
