<?php
session_start();
ob_start(); // 必ず最上部
require 'db.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// コメント削除処理
if (isset($_GET['delete_comment'])) {
    $comment_id = (int)$_GET['delete_comment'];

    $stmt = $pdo->prepare("DELETE FROM review WHERE id = ?");
    try {
        $stmt->execute([$comment_id]);
        header("Location: manage_comments.php");
        exit;
    } catch (PDOException $e) {
        echo "削除エラー: " . $e->getMessage();
    }
}


// コメント一覧取得
$sql = "
    SELECT review.id, review.comment, review.rating_clarity, review.rating_homework, review.created_at,
           lecture.lecture_name
    FROM review
    JOIN lecture ON review.lecture_id = lecture.id
    ORDER BY review.created_at DESC
";
$comments = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>コメント管理</title>
  <style>
    body { font-family: sans-serif; padding: 20px; background: #f4f4f4; }
    table { border-collapse: collapse; width: 100%; background: #fff; }
    th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
    th { background: #eee; }
    a { color: #d00; text-decoration: none; }
    a:hover { text-decoration: underline; }
  </style>
</head>
<body>

<h2>📝 コメント管理画面</h2>

<table>
  <tr>
    <th>ID</th>
    <th>講義名</th>
    <th>内容</th>
    <th>分かりやすさ</th>
    <th>課題の量</th>
    <th>投稿日時</th>
    <th>操作</th>
  </tr>
  <?php foreach ($comments as $c): ?>
    <tr>
      <td><?= htmlspecialchars($c['id']) ?></td>
      <td><?= htmlspecialchars($c['lecture_name']) ?></td>
      <td><?= nl2br(htmlspecialchars($c['comment'])) ?></td>
      <td><?= htmlspecialchars($c['rating_clarity']) ?></td>
      <td><?= htmlspecialchars($c['rating_homework']) ?></td>
      <td><?= htmlspecialchars($c['created_at']) ?></td>
      <td>
        <a href="manage_comments.php?delete_comment=<?= $c['id'] ?>"
           onclick="return confirm('このコメントを削除しますか？')">削除</a>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

</body>
</html>
