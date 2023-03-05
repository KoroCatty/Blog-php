<?php
// DB connection
include_once("./DB/connect.php");
dbConnect();

// Functions
require_once("Includes/Functions.php");

// Sessions
require_once("Includes/Sessions.php");
?>

<?php
// from name="Submit" ボタンから取得
if (isset($_POST["Submit"])) {
  $Category = $_POST["CategoryTitle"];

  // if the empty form, redirect to Categories.php (エラーメッセはセッションに渡す)
  if (empty($Category)) {
    $_SESSION["ErrorMessage"] = "All fields must be filled out";
    Redirect_to("Categories.php");
  } 

  

}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Categories</title>
  <link rel="stylesheet" href="./dist/app.css">

  <!-- fontawesome v6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <!--  -------->
  <!-- Navbar -->
  <!-- ------ -->

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="MyProfile.php" class="navbar-brand">KOJIMA.COM</a>

      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarcollapseCMS">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarcollapseCMS">

        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="Dashboard.php" class="nav-link">
              <i class="fas fa-user text-success"></i> My Profile</a>
          </li>
          <li class="nav-item">
            <a href="Posts.php" class="nav-link">Dashboard</a>
          </li>
          <li class="nav-item">
            <a href="Categories.php" class="nav-link">Posts</a>
          </li>
          <li class="nav-item">
            <a href="Admins.php" class="nav-link">Categories</a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">Manage Admins</a>
          </li>
          <li class="nav-item">
            <a href="Comments.php" class="nav-link">Comments</a>
          </li>
          <li class="nav-item">
            <a href="Blog.php?page=1" class="nav-link">Live Blog</a>
          </li>
        </ul>

        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="Logout.php" class="nav-link text-danger">
              <i class="fas fa-user-times"></i> Logout</a>
          </li>
        </ul>

      </div>
    </div>
  </nav>

  <!--  -------->
  <!-- header -->
  <!-- ------ -->
  <header class="bg-dark text-white py-3">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1 class=""><i class="fas fa-edit"></i>Mange Categories</h1>
        </div>
      </div>
    </div>
  </header>

  <!--  -------->
  <!-- Main Area -->
  <!-- ------ -->
  <section class="container py-2 mb-4 mainAreaCat">
    <div class="row categoryMain">
      <div class="offset-lg-1 col-lg-10 categoryMain__item">


      <?php
      echo ErrorMessage();
      echo SuccessMessage(); 
      ?>
        <form action="Categories.php" class="" method="post">
          <div class="card mb-3">
            <div class="card-header">
              <h1 class="">Add New Category</h1>
            </div>

            <div class="card-body bg-dark">
              <div class="form-group">
                <label for="title">
                  <span class="FieldInfo">
                    Category Title:
                  </span>
                </label>
                <input class="form-control" type="text" name="CategoryTitle" id="title" placeholder="Type title here" value="">
              </div>

              <div class="row">
                <div class="col-lg-6 mb-2">
                  <a href="Dashboard.php" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i>Back To Dashboard
                  </a>
                </div>
                <div class="col-lg-6 mb-2">

                  <button class="btn btn-success btn-block" type="submit" name="Submit">
                    <i class="fas fa-check"></i>Publish
                  </button>
                  
                </div>
              </div>
            </div>

          </div>
        </form>
      </div>
    </div>
  </section>










  <!--  -------->
  <!-- Footer -->
  <!-- ------ -->
  <footer class="bg-dark text-white">
    <div class="container">
      <div class="row">
        <!-- <div class="col"> -->
        <p class="lead text-center">
          &copy;KOJIMA ---- <span id="year"></span> All Right Reserved.
        </p>
      </div>
    </div>
    <!-- </div> -->
  </footer>


  <script src="./dist/app.js"></script>

</body>

</html>