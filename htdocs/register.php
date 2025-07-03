<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>新規登録 - 履修お助けいじばん</title>
</head>
<body>


  <!-- タイトル -->
  <h2>履修お助けいじばん</h2>
  <h3>新規登録</h3>

  <!-- 登録フォーム -->
  <form action="register.php" method="POST">
    <p>
      <label>氏名<br>
        <input type="text" name="name" required>
      </label>
    </p>
    <p>
      <label>学籍番号<br>
        <input type="text" name="username" required>
      </label>
    </p>
    <p>
      <label>パスワード<br>
        <input type="password" name="password" required>
      </label>
    </p>
    <p>
      <input type="submit" value="登録">
    </p>
  </form>


</body>
</html>

<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?,?)");

    try {
        $stmt->execute([$username, $password]); 
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        echo "登録失敗: " . $e->getMessage();
    }
}
?>
