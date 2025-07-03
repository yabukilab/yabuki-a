<?php
require_once 'db.php';

if (!isset($_GET['id'])) {
    die("IDが指定されていません。");
}

$lecture_id = intval($_GET['id']);

// 講義取得
$stmt = $pdo->prepare("SELECT * FROM lecture WHERE id = ?");
$stmt->execute([$lecture_id]);
$lecture = $stmt->fetch(PDO::FETCH_ASSOC);

// 平均評価取得（SQLで平均をまとめて取得）
$avgStmt = $pdo->prepare("
    SELECT 
        AVG(rating_clarity) AS avg_clarity,
        AVG(rating_homework) AS avg_homework
    FROM review
    WHERE lecture_id = ?
");
$avgStmt->execute([$lecture_id]);
$avg = $avgStmt->fetch(PDO::FETCH_ASSOC);

// nullチェックと平均の丸め
$clarity = round($avg['avg_clarity'], 1);
$homework = round($avg['avg_homework'], 1);

// 全体平均（2項目の平均値）
$total_avg = round(($clarity + $homework) / 2, 1);

// コメントと評価取得
$stmt = $pdo->prepare("SELECT * FROM review WHERE lecture_id = ? ORDER BY created_at DESC");
$stmt->execute([$lecture_id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>



<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>履修お助けいじばん</title>
    <link rel="stylesheet" href="1.css">
</head>
<body>
    <div class="header">履修お助けいじばん</div>

    <div class="lecture-box">
        <div class="lecture-title">
            <strong><?= htmlspecialchars($lecture['lecture_name']) ?></strong>
            <strong>総合評価：<?= $total_avg ?>　
            <span class="stars">
                <?= str_repeat("★", floor($total_avg)) ?><?= str_repeat("☆", 5 - floor($total_avg)) ?>
            </span>
            </strong>
        </div>
        <div class="lecture-meta">
            <strong>氏名　</strong><a href="teacher.php?id=<?= $lecture['teacher_id'] ?>">
                        <?= htmlspecialchars($lecture['name']) ?>
                    </a><br>
            <strong>講義内容</strong><br>
            <?= nl2br(htmlspecialchars($lecture['lecture_content'])) ?><br>
        </div>

        <div class="comments">
            <?php if ($reviews): ?>
                <?php foreach ($reviews as $r) : ?>
                    <div class="comment-row">
                        <span class="comment-text">
                        <?= nl2br(htmlspecialchars($r['comment'])) ?>
                        </span>
                        <span class="comment-text">
                            分かりやすさ：
                            <span class="stars">
                            <?= str_repeat("★", $r['rating_clarity']) ?>
                            <?= str_repeat("☆", 5 - $r['rating_clarity']) ?>
                            </span>
                            <br>
                            課題量　　　：
                            <span class="stars">
                            <?= str_repeat("★", $r['rating_homework']) ?>
                            <?= str_repeat("☆", 5 - $r['rating_homework']) ?>
                            </span>
                        </span>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>まだコメントはありません。</p>
            <?php endif; ?>
        </div>
    </div>

    <form class="review-form" method="post" action="submit_review2.php">
        <input type="hidden" name="lecture_id" value="<?= $lecture['id'] ?>">
        <div class="rating-section">
            <strong>評価・レビュー</strong><br>
            <div class="rating-row">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <input type="radio" id="clarity<?= $i ?>" name="rating_clarity" value="<?= 6-$i ?>" required>
                    <label for="clarity<?= $i ?>">★</label>
                <?php endfor; ?>
                　さすやりか分 <!-- 星の動きの関係で分かりやすさという文章が反対になってる -->
            </div>
            <div class="rating-row">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <input type="radio" id="homework<?= $i ?>" name="rating_homework" value="<?= 6-$i ?>" required>
                    <label for="homework<?= $i ?>">★</label>
                <?php endfor; ?>
                　　　　量題課<!-- 星の動きの関係で課題量という文章が反対になってる -->
            </div>
        </div>
        <textarea name="comment" placeholder="コメントを入力してください" required></textarea><br>
        <button type="submit">送信</button>
    </form>
</body>
</html>
