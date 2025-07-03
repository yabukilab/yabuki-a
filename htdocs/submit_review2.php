<?php
require_once 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $lecture_id = intval($_POST['lecture_id']);
    $clarity = intval($_POST['rating_clarity']);
    $homework = intval($_POST['rating_homework']);
    $comment = trim($_POST['comment']);

    if (
        $lecture_id && $clarity >= 1 && $clarity <= 5 &&
        $homework >= 1 && $homework <= 5 &&
        $comment !== ""
    ) {
        $stmt = $pdo->prepare("
            INSERT INTO review (lecture_id, rating_clarity, rating_homework, comment)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$lecture_id, $clarity, $homework, $comment]);
    }

    header("Location: Lecture content.php?id=" . $lecture_id);
    exit();
}
?>
