<?php
// DB connection
// include_once("./DB/connect.php");
// dbConnect();

// Functions
require_once("includes/Functions.php");

// Sessions
require_once("includes/Sessions.php");




$_SESSION["UserId"] = null;
$_SESSION["UserName"] = null;
$_SESSION["AdminName"] = null;
session_destroy();
Redirect_to("Login.php");