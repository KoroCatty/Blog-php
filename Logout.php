<?php
// DB connection
// include_once("./DB/connect.php");
// dbConnect();

// Functions
require_once("Includes/Functions.php");

// Sessions
require_once("Includes/Sessions.php");




$_SESSION["UserId"] = null;
$_SESSION["UserName"] = null;
$_SESSION["AdminName"] = null;
session_destroy();
Redirect_to("Login.php");