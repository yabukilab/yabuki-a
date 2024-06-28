<?php
// データベース接続情報
$servername = "localhost";
$username = "testuser";
$password = "pass";
$dbname = "mydb";

// フォームからの入力を取得
$user = $_POST['username'];
$pass = $_POST['password'];

// デバッグ情報を追加
echo "Received username: $user<br>";
echo "Received password: $pass<br>";

// データベース接続の確立
$conn = new mysqli($servername, $username, $password, $dbname);

// 接続を確認
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ユーザーの検証
$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $user);
if ($stmt->execute() === false) {
    die("Execute failed: " . $stmt->error);
}
$stmt->store_result();
if ($stmt->num_rows > 0) {
$stmt->bind_result($hashed_password);
$stmt->fetch();
}
if (password_verify($pass, $hashed_password)) {
    // ログイン成功
    echo "Login successful! Welcome to the home page.";
    // ここでホームページにリダイレクトすることもできます
    header('Location: home.html');
} else {
    // ログイン失敗
    echo "IDまたはパスワードが間違えています。";
    // ユーザーが存在しない
    echo "IDまたはパスワードが間違えています。";
}


$stmt->close();
$conn->close();
?>
