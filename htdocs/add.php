<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>管理者 - 履修お助けいじばん</title>
</head>
<body>


  <!-- タイトル -->
  <h2>履修お助けいじばん</h2>
  <h3>講義内容管理</h3>
  <!-- 登録フォーム -->
  <form action="add.php" method="POST">
    <p>
      <label>講義名<br>
        <input type="text" name="lecture_name" required size="30">
      </label>
    </p>
    <p>
      <label>氏名<br>
        <input type="text" name="name" required>
      </label>
    </p>
    <p>
      <label>内容<br>
        <input type="text" name="lecture_content" required size="100" style="height: 100px;">
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
    $lecture_name = trim($_POST['lecture_name']);
    $name = trim($_POST['name']);
    $lecture_content = trim($_POST['lecture_content']);

    $stmt = $pdo->prepare("INSERT INTO lecture (lecture_name, name, lecture_content) VALUES (?,?,?)");

    try {
        $stmt->execute([$lecture_name, $name, $lecture_content]); 
        header("Location: login.php");
        exit;
    } catch (PDOException $e) {
        echo "登録失敗: " . $e->getMessage();
    }
}
?>
