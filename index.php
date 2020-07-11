<?php

require_once('functions.php');

// DB接続
$pdo = connect_db();

$task = array();

$sql = "select * from todo_app where type != 'deleted' order by seq";
 $stmt = $pdo->prepare($sql);
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
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
// var_dump($result);
// exit();
// // json出力(Ajaxの準備)
// header("Content-Type: application/json; charset=UTF-8");
// echo json_encode($result);
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Todo App</title>
  <link rel="stylesheet" href="style.css">
  <!-- jQueryCDN -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script> 
</head>
<body>
  <h1>TODOアプリ</h1>
  <p>
    <input type="text" name="" id="title">
    <input type="button" value="追加" id="addTask">
  </p>
  <ul id="tasks">
    <?php foreach($results as $task): ?>
      <!-- <form action="" id="form" method="post"> -->
      <li id="task_<?= h($task['id']); ?>" data-id="<?= h($task['id']); ?>">
      <input type="checkbox" class="checkTask" <?php if ($task['type']==='done'){echo "checked";} ?>>
      <span class="<?= h($task['type']);?>"><?= $task['title'] ?></span><span 
      <?php if($task['type']=='notyet'){echo 'class="editTask"';}?> >
      [編集]</span><span class="deleteTask">[削除]</span>
      </li>
    <!-- </form> -->
    <?php endforeach ?>
  </ul>

  <script>
    $(function() {
       $('#title').focus();
       $('#addTask').click(function(){
        var title = $(this).prev().val();
        $.post('_ajax_add_task.php', {
          'title': title
        })
        .done(function(rs) {
          var e = $(
            '<li id="task_'+rs+'" data-id="'+rs+'"> ' +
            '<input type="checkbox" class="checkTask">'+'<span></span>' +
            '<span class="editTask">[編集]</span>' +
            '<span class="deleteTask">[削除]</span>' +
            '</li>'
          );
          $('#tasks').append(e).find('li:last span:eq(0)').text(title);
        });
       });


      // 編集するを押すと入力覧が出る
      $('.editTask').click(function(){
        var id = $(this).parent().data('id');
        var title = $(this).prev().text();
        // console.log(title);
        $('#task_' + id)
        .empty()
        .append($('<input type="text">').attr('value', title))
        .append('<input type="button" value="更新" class="updateTask">');
        $('#task_' + id + 'input:eq(0)').focus();
      });


      // 編集Ajax
      $(document).on('click','.updateTask',function() {
        var id = $(this).parent().data('id');
        var title = $(this).prev().val();
        // console.log(title);
        $.post('_ajax_update_task.php', {
         'id': id,
         'title':title
        })
        .done(function(rs){
          var e = $(
            '<input type="checkbox" class="checkTask">'+'<span></span>' +
            '<span class="editTask">[編集]</span>' +
            '<span class="deleteTask">[削除]</span>'
          );
          $('#task_'+id).empty().append(e).find('span:eq(0)').text(title);
        })
      });



      // チェックボックスがつくとnoyyetがdoneになる
      $('.checkTask').click(function(){
        var id = $(this).parent().data('id');
        var title = $(this).next();
        $.post('_ajax_check_task.php', {
         'id': id
        })
        .done(function(res){
          if(title.hasClass('done')){
            title.removeClass('done').next().addClass('editTask');
          }else {
            title.addClass('done').next().removeClass('editTask');

          }
        })
      });
      // 削除処理
      $('.deleteTask').click(function() {
        // alert('a');
        if(confirm('本当に削除しますか？')) {
          var id = $(this).parent().data('id');
          console.log(id);
          $.post('_ajax_delete_task.php', {
            'id': id
          })
          .done(function(rs) {
            $('#task_' + id).fadeOut(800);
            // cinsole.log('a');

          })
          .fail(function(e) {
            console.log('ajax通信ダメでした');
          console.log(e);
          });
        }
      });
    });
  </script>
</body>
</html>
