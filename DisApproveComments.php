<?php
// DB connection
include_once("./DB/connect.php");
$ConnectingDB = dbConnect();

// Functions
require_once("includes/Functions.php");

// Sessions
require_once("includes/Sessions.php");

?>


<?php 
// Approve ボタンが押されて　id があれば実行される
if(isset($_GET["id"])) { 
$SearchQueryParameter = $_GET["id"];
global $ConnectingDB; // 関数内からアクセスするため

// Login.phpで作ったセッションを使用
$Admin = $_SESSION["AdminName"];

// UPDATE comments table を下記にアップデート(statusの後はコンマがいる)
$sql = "UPDATE comments SET status='OFF',  approvedby='$Admin' WHERE id ='$SearchQueryParameter'";

$Execute = $ConnectingDB->query($sql);

// もしexcuteできたなら実行
if ($Execute) {
  $_SESSION["SuccessMessage"] = "Comment Dis-Approved Successfully";
  Redirect_to("Comments.php");

} else {
  $_SESSION["ErrorMessage"] = "sth went wrong. Try again";
  Redirect_to("Comments.php");
}

}