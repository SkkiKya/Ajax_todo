CREATE database todo_app;
grant all on todo_app.* to deuser@localhost idemtified by 'EgioWg6';

use todo_app
create table tasks (
  id int not null auto_increment primary key,
  seq int not null,
  type enum('notyet', 'done', 'deleted') default 'notyet', 
  title text, 
  created detetime,
  modified deatetime,
  Key type(type),
  KEY seq(seq)
);

insert into tasks (seq, type, title, created, modified) values
 (1, 'notyet', '牛乳を買う', now(), now()),
 (2, 'notyet', '提案書を作る', now(), now()),
 (3, 'done', '映画を見る', now(), now()),