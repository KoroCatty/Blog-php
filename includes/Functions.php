<!-- DB connect -->
<?php require_once("./DB/connect.php"); 
$ConnectingDB = dbConnect();
?>
<?php
function Redirect_to($New_Location) {
header("Location:". $New_Location); // go to this location
exit;
}


// 現在時刻を表示する関数を定義
function getTime() {
// タイムゾーンの設定
date_default_timezone_set("Asia/Tokyo");

$CurrentTime = time();

// 現在時刻を取得して格納  strftime()は非推奨になった
$DateTime = date("F j, Y, g:i a");    
// echo $DateTime;

return $DateTime;
}

// Admins.php で使用する関数を定義
function CheckUserNameExistsOrNot( $UserName ) {
  global $ConnectingDB;

  // adminsテーブルの中の username内に 同じ 名前があるかチェック
  $sql = "select username from admins WHERE username = :userName";
  $stmt = $ConnectingDB->prepare($sql);

  // $UserName
  $stmt->bindValue(':userName', $UserName);
  $stmt->execute();

  // PDOStatement->rowCount() — 直近の SQL ステートメントによって作用したDBの行数を返す // 行の数を返します。
  $Result = $stmt->rowcount(); // １か、０を返す

  // あったらtrueを返し、Admins.php内でエラーを起こさせる (これでvalidationできる)
  if ($Result == 1 ) {
    return true;

  } else {
    return false;
  }


}
