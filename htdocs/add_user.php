<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
    try {
        $stmt->execute([':username' => $username, ':password' => $password]);
        echo "User successfully registered.";
    } catch (PDOException $e) {
        echo "Error: " . h($e->getMessage());
    }
}
?>
<form method="post" action="add_user.php">
    Username: <input type="text" name="username">
    Password: <input type="password" name="password">
    <input type="submit" value="Register">
</form>
