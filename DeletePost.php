<?php
// DB connection
include_once("./DB/connect.php");
$ConnectingDB = dbConnect();

// Functions
require_once("Includes/Functions.php");

// Sessions
require_once("Includes/Sessions.php");


// Functions.phpで設定した、ログインしてないと入れない様にする関数
Confirm_Login();

// URL bar (id=  1 )などを取得
$SearchQueryParameter = $_GET["id"];
?>

<?php
// =========================================
// Fetching Existing Content to delete easily
// =========================================
global $ConnectingDB;

// Get URL parameter from URL Bar
// $SearchQueryParameter = $_GET["id"];  // id=8 などを取得

// SQL文でデータをDBから取得
$sql = "select * from posts where id = '$SearchQueryParameter' ";
$stmt = $ConnectingDB->query($sql);

// postsテーブルから各カラムを取得　そしてこのデータをHTMLに注入し、editしやすくする
while ($DataRows = $stmt->fetch()) {
  $TitleToBeDeleted = $DataRows["title"];
  $CategoryToBeDeleted = $DataRows["category"];
  $ImageToBeDeleted = $DataRows["image"];
  $PostToBeDeleted = $DataRows["post"];
}



// echo $ImageToBeUpdated;
?>

<?php
if (isset($_POST["Submit"])) {

  // Query to Delete Post in DB when everything is fine 
  global $ConnectingDB;

  // idと一致するものだけ消す
  $sql = "DELETE FROM posts WHERE id = $SearchQueryParameter";

  $Execute = $ConnectingDB->query($sql);

  // var_dump($Execute); // Debugging

  // DBとやり取りするときはエラーが起きやすいのでIF文使用
  if ($Execute) {

    // Uploadsフォルダにある画像ファイルを格納
    $Target_Path_To_DELETE_Image = "Uploads/$ImageToBeDeleted";

    // この関数で画像を消す
    unlink($Target_Path_To_DELETE_Image);

    // 一番最後のIDを表示させる lastInsertID()関数をDBを通して実行
    $_SESSION["SuccessMessage"] = "あなたのPost with id : " . $ConnectingDB->lastInsertID() . "DELETED Successfully!!!!!!";

    // echo SuccessMessage();

    Redirect_to("Posts.php");
  } else {
    $_SESSION["ErrorMessage"] = "Something went wrong!";
    Redirect_to("Posts.php");
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Post</title>
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
          <h1 class=""><i class="fas fa-edit"></i>Delete Post</h1>
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
        // ここでフォーム送信時にどちらかを表示させる
        echo ErrorMessage();
        echo SuccessMessage();


        ?>

        <!-- enctype save the image to the folder wherever you want  -->
        <!-- enctype属性は、フォームの送信データのMIMEタイプを設定するための属性です。 属性値にMIMEタイプを示す文字列を指定することで、フォームのデータが送信される際に用いられるMIMEタイプを設定することができます。 -->
        <form action="DeletePost.php?id=<?php echo htmlentities($SearchQueryParameter); ?>" class="" method="post" enctype="multipart/form-data">
          <div class="card mb-3">

            <div class="card-body bg-dark">

              <!-- ----------------------- -->
              <!-- Title  -->
              <!-- ----------------------- -->
              <div class="form-group">
                <label for="title">
                  <span class="FieldInfo">
                    Category Title:
                  </span>
                </label>

                <!-- Display the current text in DB -->
                <input disabled class="form-control" type="text" name="PostTitle" id="title" placeholder="Type title here" value="<?php echo htmlentities($TitleToBeDeleted); ?>">
              </div>

              <!-- ----------------------- -->
              <!-- Category (selection)-->
              <!-- ----------------------- -->
              <div class="form-group">
                <span class="FieldInfo">Current Category: </span>

                <!-- DBにある現在のカテゴリーを表示 -->
                <span class="existCategory"><?php echo htmlentities($CategoryToBeDeleted); ?></span>

                <br />

              </div>


              <!-- ----------------------- -->
              <!-- Image File  -->
              <!-- ----------------------- -->
              <div class="form-group my-4" style="color: white;">
                <span class="FieldInfo">Current Image: </span>

                <!-- DBにある現在のimageを表示 -->
                <img src="./Uploads/<?php echo htmlentities($ImageToBeDeleted); ?>" alt="" class="editImg mb-1">

                <div class="custom-file">
                  <!-- <input type="File" name="Image" id="imageSelect" value="" class="custom-file-input"> -->

                  <br />

                  <!-- ----------------------- -->
                  <!-- POST Content(本文) -->
                  <!-- ----------------------- -->
                  <label for="Post">
                    <span class="FieldInfo">
                      Post:
                    </span>
                  </label>

                  <!-- DBにある現在の本文を表示 -->
                  <textarea disabled class="form-control" name="PostDescription" id="Post" cols="30" rows="10"><?php echo htmlentities($PostToBeDeleted); ?></textarea>

                </div>
              </div>

              <div class="form-group">
                <label for="Post" class="custom-file-label">Select Image</label>

              </div>


              <div class="row">
                <div class="col-lg-6 mb-2">
                  <a href="Dashboard.php" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i>Back To Dashboard
                  </a>
                </div>
                <div class="col-lg-6 mb-2">

                  <button class="btn btn-danger btn-block" type="submit" name="Submit">
                    <i class="fas fa-trash"></i>Delete
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