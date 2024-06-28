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

// 削除する本のIDを取得
$book_id = $_GET['id'] ?? '';

if ($book_id) {
    // 本をデータベースから削除
    $stmt = $conn->prepare("DELETE FROM books WHERE id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $book_id);
    if ($stmt->execute() === false) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();
}

$conn->close();

// ホームページにリダイレクト
header("Location: home.php");
exit();
?>
