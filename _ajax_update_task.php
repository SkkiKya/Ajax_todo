<?php


require('functions.php');

$pdo = connect_db();

$id = $_POST['id'];
$title = $_POST['title'];
// $id = 1;
// $title = 'aa';
// var_dump($id);
// var_dump($title);
// exit();

$sql = "update todo_app set title = :title, modified_datetime = sysdate() where id = :id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->bindValue(":title", $title, PDO::PARAM_STR_CHAR);
$stmt->execute();

