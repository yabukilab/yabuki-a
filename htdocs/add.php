<?php
require 'db.php';

// 講義登録処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['teacher_id'])) {
    $lecture_name = trim($_POST['lecture_name']);
    $name = trim($_POST['name']);
    $teacher_id = (int)$_POST['teacher_id'];
    $lecture_content = trim($_POST['lecture_content']);

    $stmt = $pdo->prepare("INSERT INTO lecture (lecture_name, name, teacher_id, lecture_content) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$lecture_name, $name, $teacher_id, $lecture_content]);
        header("Location: add.php");
        exit;
    } catch (PDOException $e) {
        echo "講義登録失敗: " . $e->getMessage();
    }
}

// 教員一覧を取得
$teacher_stmt = $pdo->query("SELECT id, name FROM teacher");
$teachers = $teacher_stmt->fetchAll(PDO::FETCH_ASSOC);

// 教員追加処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_teacher'])) {
    $name = trim($_POST['name']);
    $faculty = trim($_POST['faculty']);
    $department = trim($_POST['department']);
    $laboratory = trim($_POST['laboratory']);
    $photo = trim($_POST['photo']);

    $stmt = $pdo->prepare("INSERT INTO teacher (name, faculty, department, laboratory, photo) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$name, $faculty, $department, $laboratory, $photo]);
}
?>




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
      <label>教員（氏名）<br>
        <select name="teacher_id" required>
          <option value="">選択してください</option>
          <?php foreach ($teachers as $teacher): ?>
            <option value="<?= htmlspecialchars($teacher['id']) ?>">
              <?= htmlspecialchars($teacher['name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </label>
    </p>
    <p>
      <label>内容<br>
        <input type="text" name="lecture_content" required size="100" style="height: 100px;">
      </label>
    </p>
    <label>名<br>
        <input type="text" name="name" required size="30">
      </label>
    <p>
      <input type="submit" value="登録">
    </p>
  </form>

  <hr>

  <h3>➕ 教員の追加</h3>
  <form action="add.php" method="POST">
    <input type="hidden" name="add_teacher" value="1">
    <p>氏名：<input type="text" name="name" required></p>
    <p>学部：<input type="text" name="faculty" required></p>
    <p>学科：<input type="text" name="department" required></p>
    <p>研究室：<input type="text" name="laboratory"></p>
    <p>写真URL：<input type="text" name="photo"></p>
    <p><input type="submit" value="教員を追加"></p>
  </form>


</body>
</html>

