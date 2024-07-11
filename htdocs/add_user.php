<?php
require 'db.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $db->prepare("INSERT INTO user2 (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->execute();

            echo "ユーザが追加されました。";
            echo "<p><a href='login.php'>ログイン画面へ戻る。</a></p>";
        } catch (PDOException $e) {
            echo "Error: " . h($e->getMessage());
        }
    } else {
        echo "ユーザ名とパスワードは空にできません。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ追加画面</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <div class="header"></div>
    <h1>ユーザ追加画面</h1>
    <form action="add_user.php" method="post">
        <label for="username">ユーザ名:</label><br>
        <input type="text" id="username" name="username" required><br><br>
        <label for="password">パスワード:</label><br>
        <input type="password" id="password" name="password" required><br><br>
        <input type="submit" value="ユーザ追加" class="btn btn--orange">
    </form>
</body>
</html>
