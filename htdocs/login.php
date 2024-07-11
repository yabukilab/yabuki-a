<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dbServer = '127.0.0.1';
    $dbUser = 'root';
    $dbPass = '';
    $dbName = 'mydb';

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
    }
} else {
    ?>
    <!DOCTYPE html>
    <html lang="ja">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ログイン画面</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="header">本棚管理システム</div>
        <div class="container">
            <h1>ログイン画面</h1>
            <form id="loginForm" action="" method="post">
                <label for="username">ユーザ名:</label><br>
                <input type="text" id="username" name="username" required><br><br>
                <label for="password">パスワード:</label><br>
                <input type="password" id="password" name="password" required><br><br>
                <!-- ログインボタン -->
                <button type="submit" class="btn btn--orange btn--radius">ログイン</button>
            </form>
            <p>アカウントをお持ちではありませんか？<a href="add_user.php">ユーザ追加はこちら</a></p> 
        </div>
    </body>
    </html>
    <?php
}
?>

