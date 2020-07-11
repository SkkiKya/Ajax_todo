<?php


require('functions.php');

$pdo = connect_db();

$id = $_POST['id'];

$sql = "update todo_app set type = if(type = 'done', 'notyet','done'), modified_datetime = sysdate() where id = :id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();
