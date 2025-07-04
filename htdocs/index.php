<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>履修お助けいじばん</title>
  <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">

<div class="header">履修お助けいじばん</div>
  
    <br><br><div class="center-text">学籍番号とパスワードを入力して下さい</div><br>
    
    <form action="index.php" method="POST">
      <div class="center-text"><label for="student_id">学籍番号</label>　　
      <input type="text" id="student_id" name="username" required size="30"><br><br>

      <label for="password">パスワード</label>　
      <input type="password" id="password" name="password" required size="30"><br><br></div>

      <div class="center-text">
        <a href="register.php">新規登録はこちら</a>　
        <div class="button-wrapper">
          <button type="submit">ログイン</button>
        </div>
      </div>
    </form>
    <div class="center-text">
      <a href="forgot_password.php">パスワードをお忘れの方</a>
    </div>
  
</body>
</html>


<?php
require 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header("Location: top.php");
        exit;
    } else {
        header("Location: loginfail.php");
    }
}
?>