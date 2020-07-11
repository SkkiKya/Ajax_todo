<?php

require('functions.php');

$pdo = connect_db();

$id = $_POST['id'];

$sql = "update todo_app set type = 'deleted', modified_datetime = sysdate() where id = :id";

$stmt = $pdo->prepare($sql);
$stmt->bindValue(":id", $id, PDO::PARAM_INT);
$stmt->execute();

