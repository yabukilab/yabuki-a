<?php
session_start();

<<<<<<< HEAD
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
            $stmt = $db->prepare("SELECT password FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($password, $row['password'])) {
                    $_SESSION['username'] = $username;
                    header("Location: home.php");
                    exit();
                } else {
                    echo "Incorrect password.";
                }
            } else {
                echo "Username not found.";
            }
        } catch (PDOException $e) {
            echo "Error: " . h($e->getMessage());
        }
    } else {
        echo "Username and password cannot be empty.";
=======
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dbServer = '127.0.0.1';
    $dbUser = 'testuser';
    $dbPass = 'pass';
    $dbName = 'yabukia';

    $user = $_POST['username'];
    $pass = $_POST['password'];

    $dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];

    try {
        $db = new PDO($dsn, $dbUser, $dbPass, $options);

        $stmt = $db->prepare("SELECT user_id, username, password FROM users WHERE username = ?");
        $stmt->execute([$user]);

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch();
            if (password_verify($pass, $row['password'])) {
                // ログイン成功 - セッション変数を設定
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                header('Location: home.php');
                exit();
            } else {
                echo "IDまたはパスワードが間違っています。";
            }
        } else {
            echo "IDまたはパスワードが間違っています。";
        }
    } catch (PDOException $e) {
        echo "Can't connect to the database: " . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
>>>>>>> f7e1272cbd47093d38a1e7ca405720f9614fd846
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="POST" action="login.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" value="Login">
    </form>
    <p><a href="add_user.php">Add a new user</a></p>
</body>
</html>
