<?php


require('functions.php');

$pdo = connect_db();

$title = $_POST['title'];
// $title =1;

$sql = "select max(seq)+1 from todo_app where type != 'deleted'";
$stmt = $pdo->prepare($sql);
$status = $stmt->execute();

if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  // データ登録時，失敗で以下を表示
  exit('sqlError:'.$error[2]);
} else {
    // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
// fetchAll()関数でSQLで取得したレコードを配列で取得できる
$seq = $stmt->fetch(PDO::FETCH_ASSOC);  // データの出力用変数（初期値は空文字）を設定
var_dump($seq["max(seq)+1"]);
// exit();
$sql = "INSERT INTO `todo_app`(`id`, `seq`, `type`, `title`, `created_datetime`, `modified_datetime`) VALUES (null,:seq,'notyet',:title,sysdate(),sysdate())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":seq", $seq["max(seq)+1"], PDO::PARAM_INT);
    $stmt->bindValue(":title", $title, PDO::PARAM_STR_CHAR);
    $status = $stmt->execute();
    // データ登録処理後
if ($status == false) {
  // SQL実行に失敗した場合はここでエラーを出力し，以降の処理を中止する
  $error = $stmt->errorInfo();
  // データ登録時，失敗で以下を表示
  exit('sqlError:'.$error[2]);
} else {
    // 正常にSQLが実行された場合は入力ページファイルに移動し，入力ページの処理を実行する
// fetchAll()関数でSQLで取得したレコードを配列で取得できる
echo $pdo->lastInsertId(); // データの出力用変数（初期値は空文字）を設定
}
}