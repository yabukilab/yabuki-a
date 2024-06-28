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

// フォームからの入力を取得
$title = $_POST['title'] ?? '';
$author = $_POST['author'] ?? '';
$publisher = $_POST['publisher'] ?? '';

// 本をデータベースに追加
$stmt = $conn->prepare("INSERT INTO books (title, author, publisher) VALUES (?, ?, ?)");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("sss", $title, $author, $publisher);
if ($stmt->execute() === false) {
    die("Execute failed: " . $stmt->error);
}

$stmt->close();
$conn->close();

echo "Book added successfully!";
?>

<p><a href="home.php">Return to home page</a></p>
