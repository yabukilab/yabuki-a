<?php
require_once 'db.php';

if (!isset($_GET['id'])) {
    die("IDが指定されていません。");
}

$teacher_id = intval($_GET['id']);

// 教員情報を取得
$stmt = $pdo->prepare("SELECT * FROM teacher WHERE id = ?");
$stmt->execute([$teacher_id]);
$teacher = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$teacher) {
    die("教員が見つかりません。");
}

// 担当講義一覧を取得
$stmt = $pdo->prepare("SELECT * FROM lecture WHERE teacher_id = ? ORDER BY created_at DESC");
$stmt->execute([$teacher_id]);
$lectures = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 講義ID一覧を取得
$lecture_ids = array_column($lectures, 'id');

// 講義ごとの評価平均を一括取得
$ratings = [];
if (!empty($lecture_ids)) {
    $placeholders = implode(',', array_fill(0, count($lecture_ids), '?'));
    $stmt = $pdo->prepare("
        SELECT
            lecture_id,
            AVG(rating_clarity) AS avg_clarity,
            AVG(rating_homework) AS avg_homework
        FROM review
        WHERE lecture_id IN ($placeholders)
        GROUP BY lecture_id
    ");
    $stmt->execute($lecture_ids);
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
        $ratings[$row['lecture_id']] = $row;
    }
}

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

        <?php if (!empty($teacher['photo'])): ?>
    <img src="<?= htmlspecialchars($teacher['photo']) ?>" alt="教員の写真" style="width:120px; height:auto; border-radius:8px;" align="right">
<?php else: ?>
    <p>写真は登録されていません。</p>
<?php endif; ?>

        <div class="lecture-title">
            <strong>氏名　<?= htmlspecialchars($teacher['name']) ?></strong>
        </div>
        <div class="lecture-meta">
            <br><strong>所属　<?= htmlspecialchars($teacher['faculty']) ?>　<?= htmlspecialchars($teacher['department']) ?><br>
            研究室　<?= htmlspecialchars($teacher['laboratory']) ?></strong>
        </div>
        

    </div>

<?php if ($lectures): ?>
        <ul>
        <?php foreach ($lectures as $lecture): ?>
            <?php
                $id = $lecture['id'];
                $r = $ratings[$id] ?? null;
                $clarity = $r ? round($r['avg_clarity'], 1) : '未評価';
                $homework = $r ? round($r['avg_homework'], 1) : '未評価';
                $total = ($r && $r['avg_clarity'] && $r['avg_homework']) ? round(($r['avg_clarity'] + $r['avg_homework']) / 2, 1) : '未評価';
            ?>
            <li>
                <div class="lecture-title">
                <a href="Lecture content.php?id=<?= $lecture['id'] ?>">
                    <strong><?= htmlspecialchars($lecture['lecture_name']) ?></strong>
                </a>
               
                
                <?php if (is_numeric($total)): ?>
                    <strong>
                        <?= is_numeric($total) ? "$total 点" : $total ?>
                    <span class="stars">
                        <?= str_repeat("★", floor($total)) ?>
                        <?= str_repeat("☆", 5 - floor($total)) ?>
                    </span>
                    </strong>
                <?php endif; ?>
                </div>
            </li>
            <hr>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>この教員の担当講義はまだ登録されていません。</p>
    <?php endif; ?>
</body>
</html>