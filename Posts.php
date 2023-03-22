<?php
// DB connection
include_once("./DB/connect.php");
$ConnectingDB = dbConnect();

// Functions
require_once("Includes/Functions.php");

// Sessions
require_once("Includes/Sessions.php");

// 自分自身のページに飛ばすものを定義し、それをセッションに格納
// 現在のスクリプトが実行されているサーバの IP アドレスを返すもの
$_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];

// Functions.phpで設定した、ログインしてないと入れない様にする関数
Confirm_Login();
?>

<!DOCTYPE html>
<html lang="en">
  
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Posts</title>
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
          <h1 class=""><i class="fas fa-edit"></i>Blog Posts</h1>
        </div>

        <!-- Post -->
        <div class="col-lg-3 mb-2">
          <a href="AddNewPost.php" class="btn btn-primary btn-block">
            <i class="fas fa-edit"></i>Add New Post
          </a>
        </div>

        <!-- Category -->
        <div class="col-lg-3 mb-2">
          <a href="Categories.php" class="btn btn-info btn-block">
            <i class="fas fa-folder-plus"></i>Add New Category
          </a>
        </div>

        <!-- Admin -->
        <div class="col-lg-3 mb-2">
          <a href="Admins.php" class="btn btn-warning btn-block">
            <i class="fas fa-user-plus"></i>Add New Admin
          </a>
        </div>

        <!-- Comments -->
        <div class="col-lg-3 mb-2">
          <a href="Comments.php" class="btn btn-success btn-block">
            <i class="fas fa-check"></i>Add New Comments
          </a>
        </div>

      </div>
    </div>
  </header>

  <!--  -------->
  <!-- Main Area -->
  <!-- ------ -->

  <!-- fetch every single post from DB -->
  <section class="container py-2 mb-r">
    <div class="row">
      <div class="col-lg-12">

      <!-- ここに結果を表示 -->
        <?php
        echo ErrorMessage();
        echo SuccessMessage();
        ?>

        <table class="table table-striped table-hover">
          <thead class="thead-dark">

            <!-- 名前 -->
            <tr>
              <th>#</th>
              <th>Title</th>
              <th>Category</th>
              <th>Date&Time</th>
              <th>Author</th>
              <th>Banner</th>
              <th>Comments</th>
              <th>Action</th>
              <th>Live Preview</th>
            </tr>
          </thead>

          <?php
          // global $ConnectingDB;

          $sql = "select * from posts";

          // 指定したSQL文をデータベースに対して発行してくれる役割を持っています。
          // queryメソッドを使用して、sqlをデータベースに届けなければいけないのです。
          // sql文を実行する時は必ずDBにアクセスせなあかん
          $stmt = $ConnectingDB->query($sql);

          // idの初期化 
          $Sr = 0;

          // fetch()はPDOオブジェクトでDBからデータを取り出した際に「配列の形式を指定できる」ことを指します。 $DataRowsはこのwhile文内でのみ使用可能
          while ($DataRows = $stmt->fetch()) : // fetchで取得した値を格納

            // DBのカラム順に値を取得し格納
            $Id = $DataRows["id"];
            $DateTime = $DataRows["datetime"];
            $PostTitle = $DataRows["title"];
            $Category = $DataRows["category"];
            $Admin = $DataRows["author"];
            $Image = $DataRows["image"];
            $PostText = $DataRows["post"];
            $Sr++;
          ?>

            <tbody>
              <!-- ここでDBのpostsテーブルから取得した値を出力 -->
              <tr>
                <td><?php echo $Sr; ?></td>

                <td><?php
                    // TITLE
                    // 表示する文字数に制限をかける。 . '..' でいい感じに表示する
                    // strlen()文字列のバイト数を返す
                    if (strlen($PostTitle) > 15) {

                      // substr()文字列の何文字から何文字を取り出すか指定
                      $PostTitle = substr($PostTitle, 0, 15) . '..';
                    }
                    echo $PostTitle; ?>
                </td>

                <td><?php
                    // CATEGORY
                    if (strlen($Category) > 8) {
                      $Category = mb_substr($Category, 0, 8) . '..';
                    }
                    echo $Category; ?></td>

                <td><?php
                    // DATE & TIME
                    if (strlen($DateTime) > 11) {
                      $DateTime = mb_substr($DateTime, 0, 11) . '..';
                    }
                    echo $DateTime; ?></td>

                <td><?php
                    // AUTHOR NAME
                    if (strlen($Admin) > 6) {
                      $Admin = substr($Admin, 0, 6) . '..';
                    }
                    echo $Admin; ?></td>

                <!-- imageを表示 -->
                <td><img src="Uploads/<?php echo $Image; ?>" width="170px;" height="100px;"></td>
                <td>Comments</td>
                <td>
                  <!-- Edit button -->
                  <a href="./EditPost.php?id=<?php echo htmlentities($Id); ?>" class="">
                    <span class="btn btn-warning">Edit</span>
                  </a>

                  <!-- Delete Button -->
                  <a href="DeletePost.php?id=<?php echo htmlentities($Id); ?>" class="">
                    <span class="btn btn-danger">Delete</span>
                  </a>
                </td>
                <td>
                <a href="FullPost.php?id=<?php echo htmlentities($Id); ?>" target="_blank"><span class="btn btn-primary">Live Preview</span></a>
                </td>
              </tr>
            </tbody>
          <?php endwhile; ?>

        </table>
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