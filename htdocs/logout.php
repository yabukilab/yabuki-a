<?php
session_start();

// セッション変数をすべて解除
$_SESSION = [];

// セッションクッキーも削除（存在していれば）
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
}

// セッション自体を破棄
session_destroy();

// ログインページにリダイレクト
header("Location: index.php");
exit;
