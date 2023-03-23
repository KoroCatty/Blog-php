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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <link rel="stylesheet" href="./dist/app.css">
  <title>Comments</title>
</head>

<body>
  <!-- NAVBAR -->
  <div style="height:10px; background:#27aae1;"></div>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand"> JAZEBAKRAM.COM</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarcollapseCMS">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item">
            <a href="MyProfile.php" class="nav-link"> <i class="fas fa-user text-success"></i> My Profile</a>
          </li>
          <li class="nav-item">
            <a href="Dashboard.php" class="nav-link">Dashboard</a>
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
            <a href="Blog.php?page=1" class="nav-link" target="_blank">Live Blog</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a href="Logout.php" class="nav-link text-danger">
              <i class="fas fa-user-times"></i> Logout</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <div style="height:10px; background:#27aae1;"></div>
  <!-- NAVBAR END -->
  <!-- HEADER -->
  <header class="bg-dark text-white py-3">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1><i class="fas fa-comments" style="color:#27aae1;"></i> Manage Comments</h1>
        </div>
      </div>
    </div>
  </header>
  <!-- HEADER END -->

  <section class="container py-2 mb-4">
    <div class="row">
      <div class="col-lg-12">
        <h2>Un-Approved Comments</h2>

        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr>
              <th>No. </th>
              <th>Name</th>
              <th>Date&Time</th>
              <th>Comment</th>
              <th>Approve</th>
              <th>Action</th>
              <th>Details</th>
            </tr>
          </thead>



          <?php
          // 関数内からグローバル変数にアクセスする際に必要
          global $ConnectingDB;

          // statusが OFFのやつのみ を最新順で取得する
          $sql = "select * from comments where status = 'OFF' ORDER BY id desc ";
          $Execute = $ConnectingDB->query($sql);

          // 
          $SrNo = 0;

          // DB のカラムをループで取り出す
          while ($DataRows = $Execute->fetch()) :
            $CommentId = $DataRows["id"];
            $DateTimeOfComment = $DataRows["datetime"];
            $CommenterName = $DataRows["name"];
            $CommentContent = $DataRows["comment"];
            $CommentPostId = $DataRows["post_id"]; // use this in Admin
            $SrNo++; // increment

            // 文字列のバイト数を見る関数 と文字列を何文字目から何文字目かを取り出す関数(10文字まで)
            if (mb_strlen($CommenterName) > 10 ) {
              $CommenterName = mb_substr($CommenterName, 0, 10) . '...';
            }
            if (mb_strlen($DateTimeOfComment) > 26 ) {
              $DateTimeOfComment = mb_substr($DateTimeOfComment, 0, 26) . '...';
            }
          ?>

          <!-- HTMLに出力（テーブル内） -->
            <tbody>
              <tr>
                <td><?php echo htmlentities( $SrNo ); ?></td>
                <td><?php echo htmlentities($CommenterName); ?></td>
                <td><?php echo htmlentities($DateTimeOfComment); ?></td>
                <td><?php echo htmlentities($CommentContent); ?></td>
                
                <!-- postsテーブルのidとこの$CommentIdはLovebirdの関係で繋がっている  -->
                <td><a class="btn btn-success" href="ApproveComment.php?id=<?php echo $CommentId; ?>">Approve</a></td>

                <td><a class="btn btn-danger" href="DeleteComment.php?id=<?php echo $CommentId; ?>">Delete</a></td>

                <td><a class="btn btn-primary" href="FullPost.php?id=<?php echo $CommentPostId; ?>" target="_blank">Live Preview</a></td>
              </tr>

            </tbody>
          <?php endwhile; ?>
        </table>
      </div>
    </div>
  </section>







  <!-- FOOTER -->
  <footer class="bg-dark text-white">
    <div class="container">
      <div class="row">
        <div class="col">
          <p class="lead text-center">Theme By | Jazeb Akram | <span id="year"></span> &copy; ----All right Reserved.</p>
          <p class="text-center small"><a style="color: white; text-decoration: none; cursor: pointer;" href="http://jazebakram.com/coupons/" target="_blank"> This site is only used for Study purpose jazebakram.com have all the rights. no one is allow to distribute copies other then <br>&trade; jazebakram.com &trade; Udemy ; &trade; Skillshare ; &trade; StackSkills</a></p>
        </div>
      </div>
    </div>
  </footer>
  <div style="height:10px; background:#27aae1;"></div>
  <!-- FOOTER END-->

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  <script>
    $('#year').text(new Date().getFullYear());
  </script>
</body>

</html>