<?php
// DB connection
include_once("./DB/connect.php");
dbConnect();

// Functions
require_once("Includes/Functions.php");

// Sessions
require_once("Includes/Sessions.php");

//Header
include("./templates/Header.php");


?>

<div class="loader">
  <p class="txt">ふわっと出す</p>
</div>


<main class="main">

  <h1 class="mainTitle">
  this is the index page!
  </h1> 


</main>


<script>

</script>

<?php
// Footer
include("./templates/Footer.php")
?>