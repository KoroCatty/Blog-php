<?php
// DB connection
include_once("./app/database/connect.php");
?>

<?php
// カテゴリー名を数字の1からちゃんとした名前に表示させる
// 引数：数字
// 返り値：カテゴリーの文字列
function setCategoryName($category) {
  if ($category === "1") { // PHPは動的型付け言語で、ここに int を入れても string に変換される
    return 'BLOG';
  } elseif ($category === '2') {
    return 'Daily Life';
  } else {
    return 'Other';
  }
}
?>



<?php
// 2.詳細ページでidを受け取る
// PHPの　$_GETでidを取得
$id = $_GET['id'];

// URLに付いてる id＝　がない時はエラー出力
if (empty($id)) {
  exit('You have a wrong ID');
}

// DBに接続する関数を実行するために変数に格納
$dbh = dbConnect();

// SQLを準備　(プレースホルダー :idを prepare()で、sql文を書く際はDBに繋ぐこと)
$stmt = $dbh->prepare('select * from blog where id = :id');

// bindValue()は引数が３つ必要 (プレースホルダー、実際の値、その値の型)
$stmt->bindValue(':id', (int)$id, PDO::PARAM_INT); // 明示的に(int)指定

// SQL実行
$stmt->execute();

// 全ての結果をarrayで取得
$result = $stmt->fetch(PDO::FETCH_ASSOC);

// 存在しないidがきた場合エラー出力
if (!$result) {
  exit('There is No Blog');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BLOG DETAILS</title>
  <link rel="stylesheet" href="./dist/app.css">
</head>

<body>
  <h1>Blog Details</h1>

  <!-- arrayで全データを取得してる$result で、DBのカラム名を入れて出力 -->
  <h2>title:<?php echo $result['title']; ?></h2>
  <p>Date:<?php echo $result['post_at']; ?></p>

  <!-- 数値が入ってるのをちゃんとした名前に変換する関数で直している -->
  <p>Category:<?php echo setCategoryName($result['category']); ?></p>
  <hr>
  <p>Content:<?php echo $result['content']; ?></p>






</body>

</html>