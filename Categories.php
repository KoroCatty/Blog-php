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
// Validation
// from name="Submit" ボタンから取得
if (isset($_POST["Submit"])) {

  $Category = $_POST["CategoryTitle"]; // フォームの値を格納

  $Admin = "Kazuya";



  // Functions.phpで作成した、現在時刻を取得する関数を格納
  $DateTime = getTime();


  // エラー時
  if (empty($Category)) {
    $_SESSION["ErrorMessage"] = "All fields must be filled out"; //エラーメッセをセッションに

    // Functions.phpで定義しているので、ここで指定した先にリダイレクトできるようになる
    Redirect_to("Categories.php");
  } elseif (strlen($Category) < 3) { //strlen — 文字列の長さを得る
    $_SESSION["ErrorMessage"] = "Title should be greater than 2 characters";
    Redirect_to("Categories.php");
  } elseif (strlen($Category) > 49) { //strlen — 文字列の長さを得る
    $_SESSION["ErrorMessage"] = "Title should be less than 50 characters";
    Redirect_to("Categories.php");

    // 成功時
  } else {
    // 上記のValidationをスルーしたのでDBに値を入れていく
    $sql = "insert into category(title, author, datetime)";

    // This is dummy (プレースホルダー。SQLインジェクション対策)
    $sql = $sql . "values(:categoryName, :adminName, :dateTime)";

    // connect.phpから取得した関数を格納 (sql文を実行する際に必要)
    $ConnectingDB = dbConnect();

    $stmt = $ConnectingDB->prepare($sql); // sql文は prepare()を通す必要がある

    //  bindValueは,対応する名前あるいは疑問符のプレースホルダに値をバインドする
    $stmt->bindValue(':categoryName', $Category); // 1.dummy, 2,実際の値
    $stmt->bindValue(':adminName', $Admin); // 1.dummy, 2,実際の値
    $stmt->bindValue('dateTime', $DateTime); // 1.dummy, 2,実際の値

    // 実行するコードを格納
    $Execute = $stmt->execute();

    // DBとやり取りするときはエラーが起きやすいのでIF文使用
    if($Execute) {
      // 一番最後のIDを表示させる lastInsertID()関数をDBを通して実行
      $_SESSION["SuccessMessage"] = "Category with id : ". $ConnectingDB->lastInsertID() . "ADDED Successfully!!!!!!";

// echo SuccessMessage();

      // Redirect_to("google.com");
    } else {
      $_SESSION["ErrorMessage"] = "Something went wrong!";
      Redirect_to("Categories.php");
    }

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
        // ここでフォーム送信時にどちらかを表示させる
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