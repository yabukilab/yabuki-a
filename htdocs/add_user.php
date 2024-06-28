<?php
# HTMLでのエスケープ処理をする関数（データベースとは無関係だが，ついでにここで定義しておく．）
function h($var) {
    if (is_array($var)) {
      return array_map('h', $var);
    } else {
      return htmlspecialchars($var, ENT_QUOTES, 'UTF-8');
    }
  }
  
  $dbServer = isset($_ENV['MYSQL_SERVER'])    ? $_ENV['MYSQL_SERVER']      : '127.0.0.1';
  $dbUser = isset($_SERVER['MYSQL_USER'])     ? $_SERVER['MYSQL_USER']     : 'testuser';
  $dbPass = isset($_SERVER['MYSQL_PASSWORD']) ? $_SERVER['MYSQL_PASSWORD'] : 'pass';
  $dbName = isset($_SERVER['MYSQL_DB'])       ? $_SERVER['MYSQL_DB']       : 'mydb';
  
  $dsn = "mysql:host={$dbServer};dbname={$dbName};charset=utf8";
  
  try {
    $db = new PDO($dsn, $dbUser, $dbPass);
    # プリペアドステートメントのエミュレーションを無効にする．
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    # エラー→例外
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch (PDOException $e) {
    echo "Can't connect to the database: " . h($e->getMessage());
  }

// フォームからの入力を取得
$user = $_POST['username'] ?? '';
$pass = $_POST['password'] ?? '';

// ユーザー名の重複を確認
$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    // ユーザー名が既に存在する場合
    echo "Error: The username is already taken. Please choose a different username.";
} else {
    // パスワードのハッシュ化
    $hashed_password = password_hash($pass, PASSWORD_DEFAULT);

    // 新しいユーザーをデータベースに追加
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("ss", $user, $hashed_password);
    if ($stmt->execute() === false) {
        die("Execute failed: " . $stmt->error);
    }

    echo "User added successfully!";
}

$stmt->close();
$conn->close();
?>

<p><a href="login.html">Return to login page</a></p>
