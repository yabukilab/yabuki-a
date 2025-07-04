<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>新規登録 - 履修お助けいじばん</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="header">履修お助けいじばん</div>
  <!-- タイトル -->
   
  <h2><div class="center-text">新規登録</div></h2>

  <!-- 登録フォーム -->
  <form action="register.php" method="POST">
  <div class="center-text">
    <p>
      <label><span style="margin-left: -360px; display: inline-block;">氏名</span><br>
        <input type="text" name="name" required required size="50">
      </label>
    </p>
    <p>
      <label><span style="margin-left: -330px; display: inline-block;">学籍番号</span><br>
        <input type="text" name="username" required required size="50">
      </label>
    </p>
    <p>
      <label><span style="margin-left: -310px; display: inline-block;">パスワード</span><br>
        <input type="password" name="password" required required size="50">
      </label>
    </p>
    <p>
      <input type="submit" value="登録">
    </p>
  </div>
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
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        echo "登録失敗: " . $e->getMessage();
    }
}
?>
