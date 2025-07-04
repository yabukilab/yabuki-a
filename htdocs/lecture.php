<?php
require_once 'db.php';

$sql = "
SELECT 
    lecture.*,
    teacher.name AS teacher_name,
    IFNULL(avg_table.avg_rating, 0) AS avg_rating
FROM lecture
JOIN teacher ON lecture.teacher_id = teacher.id
LEFT JOIN (
    SELECT 
        lecture_id, 
        AVG((rating_clarity + rating_homework) / 2) AS avg_rating
    FROM review
    GROUP BY lecture_id
) AS avg_table ON lecture.id = avg_table.lecture_id
";
if (!empty($_GET['keyword'])) {
    $kw = htmlspecialchars($_GET['keyword'], ENT_QUOTES);
    $sql .= " WHERE lecture.lecture_name LIKE '%$kw%' OR teacher.name LIKE '%$kw%'";
}
$sql .= " ORDER BY lecture.created_at DESC";
$stmt = $pdo->query($sql);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);




?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>履修お助けいじばん</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>履修お助けいじばん</h1>

  <div class="container">
    <!-- 左側：検索＋講義一覧 -->
    <div class="main-panel">
      <h2>科目検索</h2>
      <form method="GET">
        <input type="text" name="keyword" placeholder="検索キーワード" value="<?= isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : '' ?>" required size="150" style="height: 30px;">
        <button type="submit">検索</button>
      </form>

      <h2>講義一覧</h2>
      <p><?= count($courses) ?> 件の講義が見つかりました。</p>

      <?php if (count($courses) === 0): ?>
        <p>講義が見つかりませんでした。</p>
      <?php else: ?>
        <?php foreach ($courses as $row): ?>
          <div class="course-card">
            <h3>
  <a href="Lecture content.php?id=<?= urlencode($row['id']) ?>">
    <?= htmlspecialchars($row['lecture_name']) ?>
  </a>
</h3>
<p>担当教員：
  <a href="teacher.php?id=<?= urlencode($row['teacher_id']) ?>">
    <?= htmlspecialchars($row['teacher_name']) ?>
  </a>
</p>
            <div class="stars">
  <?php
    $rating = round($row['avg_rating']); // 四捨五入
    for ($i = 0; $i < 5; $i++) {
        echo $i < $rating ? '★' : '☆';
    }
  ?>
</div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <!-- 右側：ナビメニュー -->
    <div class="side-menu">
      <ul>
        <li>🌟 <a href="top.php">トップ画面</a></li>
        <li>🌟 <a href="ranking.php">評価ランキング</a></li>
      </ul>
    </div>
  </div>
</body>
</html>