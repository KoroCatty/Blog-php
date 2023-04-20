<?php
// DB connection
include_once("./DB/connect.php");
dbConnect();

// Functions
require_once("includes/Functions.php");

// Sessions
require_once("includes/Sessions.php");

//Header
include("./templates/Header.php");


?>

<main class="main">
  <h1 class="mainTitle">
  this is the About page!
  </h1> 


</main>




<?php
// Footer
include("./templates/Footer.php")
?>