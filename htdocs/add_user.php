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

        if ($count > 0) {
            echo "Error: Username already exists.";
        } else {
            // ユーザーを追加
            $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            $stmt->execute([$username, $password]);
            echo "User added successfully.";
        }
    } catch (PDOException $e) {
        echo "Error: " . h($e->getMessage());
    }
}
?>

<!-- HTMLフォーム -->
<form method="post" action="add_user.php">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Add User">
</form>
