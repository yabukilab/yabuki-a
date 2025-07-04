<?php
require 'db.php';
session_start();

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
        <td><a href="delete.php?delete_lecture=<?= $lecture['id'] ?>" onclick="return confirm('本当に削除しますか？')">削除</a></td>
      </tr>
    <?php endforeach; ?>
  </table>

  <hr>

  </body>
</html>
