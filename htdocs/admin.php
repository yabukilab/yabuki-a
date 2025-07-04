<?php
require 'db.php';
session_start();

// 管理者チェック（必要なら）
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

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

// 講義削除処理
if (isset($_GET['delete_lecture'])) {
    $lecture_id = (int)$_GET['delete_lecture'];
    $stmt = $pdo->prepare("DELETE FROM lecture WHERE id = ?");
    $stmt->execute([$lecture_id]);
}

// 講義一覧を取得
$lectures = $pdo->query("SELECT lecture.id, lecture.lecture_name, teacher.name AS teacher_name 
                         FROM lecture JOIN teacher ON lecture.teacher_id = teacher.id 
                         ORDER BY lecture.created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>管理者画面</title>
</head>
<body>
  <h2>📘 管理者画面</h2>

  <h3>🗑 講義一覧（削除可能）</h3>
  <table border="1">
    <tr><th>ID</th><th>講義名</th><th>教員名</th><th>削除</th></tr>
    <?php foreach ($lectures as $lecture): ?>
      <tr>
        <td><?= htmlspecialchars($lecture['id']) ?></td>
        <td><?= htmlspecialchars($lecture['lecture_name']) ?></td>
        <td><?= htmlspecialchars($lecture['teacher_name']) ?></td>
        <td><a href="admin.php?delete_lecture=<?= $lecture['id'] ?>" onclick="return confirm('本当に削除しますか？')">削除</a></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <hr>

  <h3>➕ 教員の追加</h3>
  <form action="admin.php" method="POST">
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
