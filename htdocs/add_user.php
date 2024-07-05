<?php
function h($var) {
    if (is_array($var)) {
        return array_map('h', $var);
    } else {
        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    }
}

$dbServer = isset($_ENV['MYSQL_SERVER']) ? $_ENV['MYSQL_SERVER'] : '127.0.0.1';
$dbUser = isset($_SERVER['MYSQL_USER']) ? $_SERVER['MYSQL_USER'] : 'testuser';
$dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
$dbName = isset($_SERVER['MYSQL_DB']) ? $_SERVER['MYSQL_DB'] : 'mydb';

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
        try {
            // ユーザー名の重複チェック
            $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE username = :username");
            $stmt->execute([':username' => $username]);
            $count = $stmt->fetchColumn();

            if ($count > 0) {
                echo "Username already exists. Please choose a different username.";
            } else {
                // 新しいユーザーを追加
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
                $stmt->execute([':username' => $username, ':password' => $hashed_password]);
                echo "User registered successfully.";
                echo "<p><a href='login.html'>Go to Login</a></p>";
            }
        } catch (PDOException $e) {
            echo "Error: " . h($e->getMessage());
        }
    } else {
        echo "Username and password cannot be empty.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
</head>
<body>
    <h2>Add User</h2>
    <form method="POST" action="add_user.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" value="Add User">
    </form>
    <p><a href="login.html">Go to Login</a></p>
</body>
</html>
