<?php
session_start();

// セッション変数をすべて解除
$_SESSION = array();

// セッションを破棄
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// セッションを完全に破棄
session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト</title>
    <link rel="stylesheet" href="styles.css">
</head>
<div class="header"></div>
<body>
    <h1>ログアウト完了</h1>
    <p>ログアウトが完了しました。</p>
    <p><a href="login.php">ログイン画面に戻る</a></p>
</body>
</html>
