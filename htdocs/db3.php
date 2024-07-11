<?php
require 'db.php';

$sql='DELETE FROM users';
$stmt=$db->prepare($sql);
$stmt->execute();

?>