<?php
require 'db.php';

$sql='SELECT user_id,username FROM users WHERE 1';
$stmt=$db->prepare($sql);
$stmt->execute();

$rec=$stmt->fetchall(PDO::FETCH_ASSOC);
foreach ($rec AS $r){
    echo $r['user_id'];
    echo $r['username'];    
    echo "<br>";
}
?>