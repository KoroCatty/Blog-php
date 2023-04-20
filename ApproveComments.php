<?php
// DB connection
include_once("./DB/connect.php");
$ConnectingDB = dbConnect();

// Functions
require_once("includes/Functions.php");

// Sessions
require_once("includes/Sessions.php");

// // 自分自身のページに飛ばすものを定義し、それをセッションに格納
// // 現在のスクリプトが実行されているサーバの IP アドレスを返すもの
// $_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];

// // Functions.phpで設定した、ログインしてないと入れない様にする関数
// Confirm_Login();
?>


<?php 
// Approve ボタンが押されて　id があれば実行される
if(isset($_GET["id"])) { 
$SearchQueryParameter = $_GET["id"];
global $ConnectingDB; // 関数内からアクセスするため

// Login.phpで作ったセッションを使用
$Admin = $_SESSION["AdminName"];

// UPDATE comments table を下記にアップデート(statusの後はコンマがいる)
$sql = "UPDATE comments SET status='ON',  approvedby='$Admin' WHERE id ='$SearchQueryParameter'";

$Execute = $ConnectingDB->query($sql);

// もしexcuteできたなら実行
if ($Execute) {
  $_SESSION["SuccessMessage"] = "Comment Approved Successfully";
  Redirect_to("Comments.php");

} else {
  $_SESSION["ErrorMessage"] = "sth went wrong. Try again";
  Redirect_to("Comments.php");
}

}