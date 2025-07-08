<?php
require_once 'db.php';   // DB接続

// ユーザー削除処理
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $delete_id = intval($_POST['delete_id']);
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$delete_id]);
    header("Location: user_manage.php");
    exit;
}

// ユーザー一覧取得
$stmt = $pdo->query("SELECT id, username, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ユーザー管理</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>ユーザー管理画面</h1>
    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>ユーザー名</th>
            <th>登録日時</th>
            <th>操作</th>
        </tr>
        <?php foreach ($users as $user): ?>
        <tr>
            <td><?= htmlspecialchars($user['id']) ?></td>
            <td><?= htmlspecialchars($user['username']) ?></td>
            <td><?= htmlspecialchars($user['created_at']) ?></td>
            <td>
                <form method="POST" onsubmit="return confirm('本当に削除しますか？');">
                    <input type="hidden" name="delete_id" value="<?= $user['id'] ?>">
                    <button type="submit">削除</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="top.php">← トップに戻る</a>
</body>
</html>
