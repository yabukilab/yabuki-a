<?php
require_once 'db.php';

// 講義とその評価平均を取得（総合評価＝ clarity と homework の平均）
$sql = "
    SELECT 
        l.id,
        l.lecture_name,
        l.lecture_content,
        IFNULL(AVG((r.rating_clarity + r.rating_homework) / 2), 0) AS avg_total
    FROM lecture l
    LEFT JOIN review r ON l.id = r.lecture_id
    GROUP BY l.id
    ORDER BY avg_total DESC, l.created_at DESC
";

$stmt = $pdo->query($sql);
$lectures = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>履修お助けいじばん</title>
  <link rel="stylesheet" href="1.css">
</head>
<body>
  <h1>履修お助けいじばん</h1>

  <div class="container">
    <div class="main-panel">
      <h2>評価ランキング</h2>
      <?php if ($lectures): ?>
        <?php foreach ($lectures as $lecture): ?>
            <div class="lecture-box">
                <div class="course-card">
                    <h3>
                        <div class="lecture-title">
                    <a href="Lecture content.php?id=<?= $lecture['id'] ?>">
                        <?= htmlspecialchars($lecture['lecture_name']) ?>
                    </a>
                
                    <strong><?= round($lecture['avg_total'], 1) ?>
                    <span class="stars">
                        <?= str_repeat("★", floor($lecture['avg_total'])) ?>
                        <?= str_repeat("☆", 5 - floor($lecture['avg_total'])) ?>
                    </span>
                    </strong>
                        </div>
                    </h3>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>講義が見つかりませんでした。</p>
    <?php endif; ?>
    </div>

  

  <!-- 右側：ナビメニュー -->
    <div class="side-menu">
      <ul>
        <li>🌟 <a href="a.html">トップ画面</a></li>
        <li>🌟 <a href="#">評価ランキング</a></li>
      </ul>
    </div>
  </div>
</body>
</html>