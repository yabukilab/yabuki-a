<?php
require_once 'db.php';

$sql = "SELECT * FROM lecture ORDER BY created_at DESC";
$lectures = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>講義一覧 - 履修お助けいじばん</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="header">履修お助けいじばん</div>

    <?php foreach ($lectures as $lecture): ?>
        <div class="content-box">
            <div class="lecture-info">
                <strong>
                    <a href="Lecture content.php?id=<?= $lecture['id'] ?>">
                        <?= htmlspecialchars($lecture['lecture_name']) ?>
                    </a>
                </strong><br>
                氏名：<?= htmlspecialchars($lecture['name']) ?><br>
                
            </div>
        </div>
    <?php endforeach; ?>
</body>
</html>
