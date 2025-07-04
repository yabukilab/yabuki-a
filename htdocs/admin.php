<?php
require 'db.php';
session_start();


// 講義削除処理
if (isset($_GET['delete_lecture'])) {
    $lecture_id = (int)$_GET['delete_lecture'];
    $stmt = $pdo->prepare("DELETE FROM lecture WHERE id = ?");
    try {
        $stmt->execute([$lecture_id]);
        header("Location: admin.php");
        exit;
    } catch (PDOException $e) {
        echo "講義削除エラー: " . $e->getMessage();
    }
}

// 教員削除処理
if (isset($_GET['delete_teacher'])) {
    $teacher_id = (int)$_GET['delete_teacher'];
    $stmt = $pdo->prepare("DELETE FROM teacher WHERE id = ?");
    try {
        $stmt->execute([$teacher_id]);
        header("Location: admin.php");
        exit;
    } catch (PDOException $e) {
        echo "教員削除エラー: " . $e->getMessage();
    }
}

// 講義一覧取得（教員名も表示）
$lectures = $pdo->query("
    SELECT lecture.id, lecture.lecture_name, teacher.name AS teacher_name 
    FROM lecture 
    JOIN teacher ON lecture.teacher_id = teacher.id 
    ORDER BY lecture.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);

// 教員一覧取得
$teachers = $pdo->query("SELECT * FROM teacher ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理者画面 - 講義と教員の削除</title>
    <style>
      body { font-family: sans-serif; padding: 20px; background: #f9f9f9; }
      h2, h3 { color: #333; }
      table { border-collapse: collapse; width: 100%; background: white; margin-bottom: 30px; }
      th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
      th { background: #eee; }
      a { text-decoration: none; color: #007BFF; }
      a:hover { text-decoration: underline; }
    </style>
</head>
<body>

<h2>📘 管理者画面</h2>

<h3>🗑 講義一覧（削除可能）</h3>
<table>
  <tr>
    <th>ID</th>
    <th>講義名</th>
    <th>教員名</th>
    <th>操作</th>
  </tr>
  <?php foreach ($lectures as $lecture): ?>
    <tr>
      <td><?= htmlspecialchars($lecture['id']) ?></td>
      <td><?= htmlspecialchars($lecture['lecture_name']) ?></td>
      <td><?= htmlspecialchars($lecture['teacher_name']) ?></td>
      <td>
        <a href="admin.php?delete_lecture=<?= $lecture['id'] ?>" onclick="return confirm('この講義を本当に削除しますか？')">削除</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

<h3>👨‍🏫 教員一覧（削除可能）</h3>
<table>
  <tr>
    <th>ID</th>
    <th>氏名</th>
    <th>学部</th>
    <th>学科</th>
    <th>研究室</th>
    <th>操作</th>
  </tr>
  <?php foreach ($teachers as $teacher): ?>
    <tr>
      <td><?= htmlspecialchars($teacher['id']) ?></td>
      <td><?= htmlspecialchars($teacher['name']) ?></td>
      <td><?= htmlspecialchars($teacher['faculty']) ?></td>
      <td><?= htmlspecialchars($teacher['department']) ?></td>
      <td><?= htmlspecialchars($teacher['laboratory']) ?></td>
      <td>
        <a href="admin.php?delete_teacher=<?= $teacher['id'] ?>" onclick="return confirm('この教員を本当に削除しますか？')">削除</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

</body>
</html>
