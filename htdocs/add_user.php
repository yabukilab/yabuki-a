<?php
session_start();

function h($var) {
    if (is_array($var)) {
        return array_map('h', $var);
    } else {
        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    }
}

$dbServer = '127.0.0.1';
$dbUser = 'testuser';
$dbPass = 'pass';
$dbName = 'yabukia';

$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

try {
    $db = new PDO($dsn, $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Can't connect to the database: " . h($e->getMessage());
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (!empty($username) && !empty($password)) {
        // パスワードのハッシュ化
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

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
