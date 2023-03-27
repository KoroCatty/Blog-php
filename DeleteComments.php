<?php
// DB connection
include_once("./DB/connect.php");
$ConnectingDB = dbConnect();

// Functions
require_once("Includes/Functions.php");

// Sessions
require_once("Includes/Sessions.php");

?>


<?php 
// Approve ボタンが押されて　id があれば実行される
if(isset($_GET["id"])) { 
$SearchQueryParameter = $_GET["id"];
global $ConnectingDB; // 関数内からアクセスするため



// DELETE a COMMENT
$sql = "DELETE FROM comments WHERE id ='$SearchQueryParameter'";

$Execute = $ConnectingDB->query($sql);

// もしexcuteできたなら実行
if ($Execute) {
  $_SESSION["SuccessMessage"] = "Comment Deleted Successfully";
  Redirect_to("Comments.php");

} else {
  $_SESSION["ErrorMessage"] = "sth went wrong. Try again";
  Redirect_to("Comments.php");
}

}