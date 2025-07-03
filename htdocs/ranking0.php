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
    <title>評価の高い講義一覧</title>
    <style>
        .stars { color: gold; }
        .lecture-box { margin-bottom: 20px; border-bottom: 1px solid #ccc; padding-bottom: 10px; }
    </style>
</head>
<body>
    <h1>評価の高い講義ランキング</h1>

    <?php if ($lectures): ?>
        <?php foreach ($lectures as $lecture): ?>
            <div class="lecture-box">
                <h2>
                    <a href="lecture_detail.php?id=<?= $lecture['id'] ?>">
                        <?= htmlspecialchars($lecture['lecture_name']) ?>
                    </a>
                </h2>
                <p><?= nl2br(htmlspecialchars($lecture['lecture_content'])) ?></p>
                <p>
                    総合評価：<?= round($lecture['avg_total'], 1) ?>
                    <span class="stars">
                        <?= str_repeat("★", floor($lecture['avg_total'])) ?>
                        <?= str_repeat("☆", 5 - floor($lecture['avg_total'])) ?>
                    </span>
                </p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>講義が見つかりませんでした。</p>
    <?php endif; ?>
</body>
</html>
