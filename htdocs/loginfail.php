<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>履修お助けいじばん</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <div class="header"></div>
    <h2>履修お助けいじばん</h2>
    <p>学籍番号またはパスワードが違います</p>
    <form action="login.php" method="POST">
      <label for="student_id">学籍番号</label>　　
      <input type="text" id="student_id" name="username"><br><br>

      <label for="password">パスワード</label>　
      <input type="password" id="password" name="password"><br><br>

      <div class="links">
        <a href="register.php">新規登録はこちら</a>　
        <button type="submit">ログイン</button>
      </div>
    </form>
    <div class="forgot">
      <a href="forgot_password.php">パスワードをお忘れの方</a>
    </div>
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
        header("Location: index.html");
        exit;
    } else {
        header("Location: loginfail.php");
    }
}
?>
