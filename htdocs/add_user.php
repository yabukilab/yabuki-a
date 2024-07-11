<<?php
function h($var) {
    if (is_array($var)) {
        return array_map('h', $var);
    } else {
        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    }
}

$dbServer = 'localhost';
$dbUser = 'testuser';
$dbPass = 'pass';
$dbName = 'mydb';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

try {
    $db = new PDO($dsn, $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Can't connect to the database: " . h($e->getMessage());
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = h($_POST["username"]);
    $password = password_hash(h($_POST["password"]), PASSWORD_DEFAULT);

    try {
        // 既存のユーザー名をチェック
        $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $count = $stmt->fetchColumn();

<<<<<<< HEAD
        try {
            // プリペアドステートメントを使用して安全にデータを挿入
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
            $stmt->execute();

            echo "ユーザが追加されました。";
            echo "<p><a href='login.php'>ログイン画面へ戻る。</a></p>";
        } catch (PDOException $e) {
            echo "Error: " . h($e->getMessage());
=======
        if ($count > 0) {
            echo "Error: Username already exists.";
        } else {
            // ユーザーを追加
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $password]);
            echo "User added successfully.";
>>>>>>> b39caaa359ad3c1e8019004c6376e81b23e0afcb
        }
    } catch (PDOException $e) {
        echo "Error: " . h($e->getMessage());
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
