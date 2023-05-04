<!-- DB connect -->
<?php
//  require_once("./DB/connect.php"); 
// $ConnectingDB = dbConnect();
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
// Login.phpで使用する関数 
// ハッシュ化されたDB内のパスワードと、ログイン画面で入力するハッシュ化されていないパスワードを一致させる
// =======================================
function Login_Attempt($UserName, $Password) {

  // 関数の中からグローバル変数にアクセスする時は定義しないといけない
  global $ConnectingDB;

  // プリペアドステートメントを定義する
  $sql = "SELECT * FROM admins WHERE username = :userName";
  $stmt = $ConnectingDB->prepare($sql);
  $stmt->bindValue(':userName', $UserName);
  $stmt->execute();
  
  // ユーザー名に対応するレコードを取得する
  // PDO::FETCH_ASSOCは、fetch()メソッドで取得したデータを、連想配列の形式で取得
  // 結果セット内に行が存在しない場合はfalseを返す
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  // DBのハッシュ化パスワードを格納
  $stored_password = $row['password'];

  // DBのハッシュ化パスワードと、ログインに入力されたのが一致するかどうかをチェックする
  if (password_verify($Password, $stored_password)) {
    // パスワードが一致した場合は、そのユーザーの情報を返す
    return $row;
  } else {
    // パスワードが一致しない場合は、nullを返す
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






