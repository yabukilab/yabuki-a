<?php
// データベース接続情報
$servername = "localhost";
$username = "root";
$password = ""; // データベースパスワード
$dbname = "mydb";

// データベース接続の確立
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続を確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// フォームからの入力を取得
$user = $_POST['username'] ?? '';
$pass = $_POST['password'] ?? '';

// ユーザー名の重複を確認
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // ユーザー名が既に存在する場合
    echo "Error: The username is already taken. Please choose a different username.";
} else {
    // パスワードのハッシュ化
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // 新しいユーザーをデータベースに追加
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $user, $hashed_password);
    if ($stmt->execute() === false) {
        die("Execute failed: " . $stmt->error);
    }

    echo "User added successfully!";
}

$stmt->close();
$conn->close();
?>

<p><a href="login.html">Return to login page</a></p>
