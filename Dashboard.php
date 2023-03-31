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
  <title>Dashboard</title>
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
            <a href="Posts.php" class="nav-link">Posts</a>
          </li>
          <li class="nav-item">
            <a href="Categories.php" class="nav-link">Categories</a>
          </li>
          <li class="nav-item">
            <a href="Admins.php" class="nav-link">Manage Admins</a>
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
          <h1 class=""><i class="fas fa-cog"></i>Dashboard</h1>
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
      <!-- ここに結果を表示 -->

      <?php
      echo ErrorMessage();
      echo SuccessMessage();
      ?>

      <!-- Left side area -->
      <div class="col-lg-2 d-none d-md-block">

        <!-- Posts -->
        <div class="card text-center bg-dark text-white mb-3">
          <div class="card-body">
            <h1 class="lead">Posts</h1>
            <h4 class="display-5">
              <i class="fab fa-readme"></i>
              <?php
              // ===========================
              // Total Posts (DBに何個あるか表示)
              // ===========================
              global $ConnectingDB;
              // SQLのCOUNT関数は、テーブルのレコード数を数える関数です。 *（アスタリスク）を指定すると、すべてのレコードの行数をカウントします。
              $sql = "select COUNT(*) from posts";

              $stmt = $ConnectingDB->query($sql);

              // fetch() is extracting all the data with array format. なので array_shift()が必要。
              $TotalRows = $stmt->fetch();
              // echo $TotalRows[0];
              // echo $TotalRows[1];

              //array_shift()は、配列の一番最初の要素を抜き出して返す関数です。 
              $TotalPosts = array_shift($TotalRows);
              echo $TotalPosts;
              ?>
            </h4>
          </div>
        </div>

        <!-- Categories -->
        <div class="card text-center bg-dark text-white mb-3">
          <div class="card-body">
            <h1 class="lead">Categories</h1>
            <h4 class="display-5">
              <i class="fas fa-folder"></i>
              <?php
              // ===========================
              // Total Categories (DBに何個あるか表示)
              // ===========================
              global $ConnectingDB;
              $sql = "select COUNT(*) from category";

              $stmt = $ConnectingDB->query($sql);

              $TotalRows = $stmt->fetch();

              $TotalCategories = array_shift($TotalRows);
              echo $TotalCategories;
              ?>
            </h4>
          </div>
        </div>

        <!-- Admins -->
        <div class="card text-center bg-dark text-white mb-3">
          <div class="card-body">
            <h1 class="lead">Admins</h1>
            <h4 class="display-5">
              <i class="fas fa-users"></i>
              <?php
              // ===========================
              // Total Admins (DBに何個あるか表示)
              // ===========================
              global $ConnectingDB;
              $sql = "select COUNT(*) from admins";

              $stmt = $ConnectingDB->query($sql);

              $TotalRows = $stmt->fetch();

              $TotalAdmins = array_shift($TotalRows);
              echo $TotalAdmins;
              ?>
            </h4>
          </div>
        </div>

        <!-- Comments -->
        <div class="card text-center bg-dark text-white mb-3">
          <div class="card-body">
            <h1 class="lead">Comments</h1>
            <h4 class="display-5">
              <i class="fas fa-comments"></i>
              <?php
              // ===========================
              // Total Admins (DBに何個あるか表示)
              // ===========================
              global $ConnectingDB;
              $sql = "select COUNT(*) from comments";

              $stmt = $ConnectingDB->query($sql);

              $TotalRows = $stmt->fetch();

              $TotalComments = array_shift($TotalRows);
              echo $TotalComments;
              ?>
            </h4>
          </div>
        </div>
      </div>

      <!--  ------------>
      <!-- Right side -->
      <!-- ---------- -->
      <div class="col-lg-10">
        <h1>Top posts</h1>
        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr>
              <th>No.</th>
              <th>Title</th>
              <th>Date & Time</th>
              <th>Author</th>
              <th>Comments</th>
              <th>Details</th>
            </tr>
          </thead>

          <?php
          $SrNo = 0;
          global $ConnectingDB;

          // postsテーブルから5件最新順で取得する
          $sql = "select * from posts ORDER BY id desc LIMIT 0, 5";

          $stmt = $ConnectingDB->query($sql);

          // $stmtをループしてpostsテーブル内のrowを全部出力
          while ($DataRows = $stmt->fetch()) :
            $PostId = $DataRows['id'];
            $DateTime = $DataRows['datetime'];
            $Title = $DataRows['title'];
            $Author = $DataRows['author'];
            $SrNo++; // incrementing series No
          ?>

            <!-- HTMLに出力 -->
            <tbody>
              <tr>
                <td><?php echo htmlspecialchars($SrNo); ?></td>
                <td><?php echo htmlspecialchars($Title); ?></td>
                <td><?php echo htmlspecialchars($DateTime); ?></td>
                <td><?php echo htmlspecialchars($Author); ?></td>

                <!-- ------------------------ -->
                <!-- Approveしたコメント数を表示 -->
                <!-- ------------------------ -->
                <td>
                  <span class="badge badge-success">
                    <?php
                    global $ConnectingDB;

                    // commentsテーブル内の PostId と一致し、かつ statusが　ON のコメントのみ摘出
                    $sqlApprove = "select COUNT(*) from comments WHERE post_id = '$PostId' AND status = 'ON' ";

                    $stmtApprove = $ConnectingDB->query($sqlApprove);
                    $RowsTotal = $stmtApprove->fetch();

                    // fetch()はarrayで帰ってくるので、それをstringに変換する関数
                    $Total = array_shift($RowsTotal);

                    // if the comment is 0, do not show the number
                    if ($Total) {
                      echo $Total;
                    }
                    ?>
                  </span>


                  <!-- ------------------------ -->
                  <!-- Dis-Approveしたコメント数を表示 -->
                  <!-- ------------------------ -->
                  <span class="badge text-danger">
                    <?php
                    global $ConnectingDB;

                    // commentsテーブル内の PostId と一致し、かつ statusが　ON のコメントのみ摘出
                    $sqlDisApprove = "select COUNT(*) from comments WHERE post_id = '$PostId' AND status = 'OFF' ";

                    $stmtDisApprove = $ConnectingDB->query($sqlDisApprove);
                    $RowsTotal = $stmtDisApprove->fetch();

                    // fetch()はarrayで帰ってくるので、それをstringに変換する関数
                    $Total = array_shift($RowsTotal);

                    // if the comment is 0, do not show the number
                    if ($Total) {
                      echo $Total;
                    }
                    ?>
                  </span>
                </td>

                <td>
                  <!-- その特定のidのブログページに飛ぶ -->
                  <a href="FullPost.php?id=<?php echo $PostId; ?>">
                    <span class="btn btn-info">Preview</span>
                  </a>
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