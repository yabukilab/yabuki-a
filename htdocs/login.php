<?php
session_start();

function h($var) {
    if (is_array($var)) {
        return array_map('h', $var);
    } else {
        return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    }
}

// POSTリクエストの場合にのみ、データベース接続に必要な変数を設定する
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
    $dbUser   = isset($_SERVER['MYSQL_USER'])    ? $_SERVER['MYSQL_USER']     : 'root';
    $dbPass   = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : '';
    $dbName   = isset($_SERVER['MYSQL_DB'])      ? $_SERVER['MYSQL_DB']       : 'mydb';
} else {
    // POSTリクエストでない場合は、初期値を設定する
    $dbServer = '127.0.0.1';
    $dbUser   = 'root';
    $dbPass   = '';
    $dbName   = 'mydb';
}

// データベース接続文字列の作成
$dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";

try {
    // PDOインスタンスを作成し、設定する
    $db = new PDO($dsn, $dbUser, $dbPass);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // 接続エラーが発生した場合はエラーメッセージを出力して終了する
    echo "データベースに接続できません: " . h($e->getMessage());
    exit;
}

// POSTリクエストである場合のみ、ログイン処理を実行する
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = h($_POST["username"]);
    $password = h($_POST["password"]);

    try {
        // ユーザー名を元にユーザー情報を取得するクエリを実行する
        $stmt = $db->prepare("SELECT username, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // ユーザーが存在し、パスワードが正しい場合にセッションを開始してホームページにリダイレクトする
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            header("Location: home.php");
            exit;
        } else {
            echo "ユーザー名またはパスワードが間違っています。";
        }
    } catch (PDOException $e) {
        // エラーが発生した場合はエラーメッセージを出力する
        echo "エラー: " . h($e->getMessage());
    }
}
?>

<!-- HTMLフォーム -->
<form method="post" action="login.php">
    ユーザー名: <input type="text" name="username">
    パスワード: <input type="password" name="password">
    <input type="submit" value="ログイン">
</form>
