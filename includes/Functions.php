<!-- DB connect -->
<?php require_once("./DB/connect.php"); 
$ConnectingDB = dbConnect();
?>



<?php
// =======================================
// リダイレクト
// =======================================
function Redirect_to($New_Location) {
header("Location:". $New_Location); // go to this location
exit;
}

// =======================================
// 現在時刻を表示する関数を定義
// =======================================
function getTime() {
// タイムゾーンの設定
date_default_timezone_set("Asia/Tokyo");

$CurrentTime = time();

// 現在時刻を取得して格納  strftime()は非推奨になった
$DateTime = date("F j, Y, g:i a");    
// echo $DateTime;

return $DateTime;
}

// =======================================
// Admins.php で使用する関数を定義
// =======================================
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

// =======================================
// Login.phpで使用する関数 (1 ならDBからデータを取得)
// =======================================
function Login_Attempt($UserName, $Password) {

  // 関数の中からグローバル変数にアクセスする時は定義しないといけない
  global $ConnectingDB;

      // DBの中にusername & passwordが一致しているのがあれば username & password の 1 レコード 取得
      $sql = "select * from admins 
      WHERE username = :userName AND password = :passWord LIMIT 1";
  
      // this will use the method of prepare 
      $stmt = $ConnectingDB->prepare($sql);
  
      // then bind a value respectively
      $stmt->bindValue(':userName', $UserName); // Eggman 
      $stmt->bindValue(':passWord', $Password); // aaaa
      $stmt->execute();

       // PDOStatement->rowCount() — 直近の SQL ステートメントによって作用したDBの行数を返す // 行の数を返します。
      $Result = $stmt->rowcount();  //  1 or any other number
  
      // TRUE で１ならfetchを開始
      if ($Result == 1 ) {
        // fetch the record (returning fetched data and 格納 to $Found_Account)
      return $Found_Account = $stmt->fetch();
  
      // False
      } else {
        return null;
      }
}

// =======================================
// Logout function
// =======================================
// ログインしないと入れないページにこの関数を貼り付ける　（セッションのUserIDがない状態ではエラーを返す）
function Confirm_Login() {
  if (isset($_SESSION["UserId"])) {
    return true;

  } else {
    $_SESSION["ErrorMessage"] = "Login Required";
    Redirect_to("Login.php");
  }
}






