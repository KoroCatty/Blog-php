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

// DELETE a COMMENT
$sql = "DELETE FROM category WHERE id ='$SearchQueryParameter'";
$Execute = $ConnectingDB->query($sql);

// もしexcuteできたなら実行
if ($Execute) {
  $_SESSION["SuccessMessage"] = "Category Deleted Successfully";
  Redirect_to("Categories.php");

} else {
  $_SESSION["ErrorMessage"] = "sth went wrong. Try again";
  Redirect_to("Categories.php");
}

}