 <?php

$blogs = $_POST;

// DB connection
include_once("./app/database/connect.php");

// ==============================
// Validation
// ==============================
if (empty($blogs['title'])) {
  exit('Type the Title');
}
// 文字数の制限をかける関数
if (mb_strlen($blogs['title']) > 191) {
  exit('The limitation is under 191 texts');
}


if (empty($blogs['content'])) {
  exit('Type the Content');
}
if (empty($blogs['category'])) {
  exit('Category is required');
}
if (empty($blogs['publish_status'])) {
  exit('status is required');
}
?>

<?php
// ==============================
// DBにデータを登録
// ==============================
// SQL文を格納
$sql = 'insert into blog(
  title, content, category, publish_status)
  values(
    :title, :content, :category, :publish_status)'; //プレースホルダーを使い、 prepare()で、sql文を書く際はDBに繋ぐこと)

$dbh = dbConnect();

// DBに繋いだ一番初めにTransactionすること
//オートコミットモードをオフにします。オートコミットモードがオフの間、 PDO オブジェクトを通じてデータベースに加えた変更は PDO::commit() をコールするまでコミットされません。 PDO::rollBack() をコールすると、 データベースへの全ての変更をロールバックし、 オートコミットモードに設定された接続を返します。
$dbh->beginTransaction();



// DBにデータを入れる場合は必ず try & catch　を使う(エラーが起きやすいので)
try {
  $stmt = $dbh->prepare($sql);

  //プレースホルダーを使うためのbindValue()で、引数が３つ必要 (プレースホルダー、実際の値、その値の型)
  $stmt->bindValue(':title', $blogs['title'], PDO::PARAM_STR);
  $stmt->bindValue(':content', $blogs['content'], PDO::PARAM_STR);
  $stmt->bindValue(':category', $blogs['category'], PDO::PARAM_INT);
  $stmt->bindValue(':publish_status', $blogs['publish_status'], PDO::PARAM_INT);

  // SQL実行
  $stmt->execute();

// ここでやっと Transaction のコミットをしてDBに登録をする
$dbh->commit();

echo 'Posted a blog successfully!';
} catch (PDOException $er) {

  //エラー時には何もなかったことにするために、ここでロールバックをする
  $dbh->rollBack();
  exit($e);
}
?>






 -->
