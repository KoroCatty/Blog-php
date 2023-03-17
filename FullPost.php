<?php
// DB connection
include_once("./DB/connect.php");
$ConnectingDB = dbConnect();

// Functions
require_once("Includes/Functions.php");

// Sessions
require_once("Includes/Sessions.php");
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

  <title>Blog Page</title>
</head>


<body>
  <!-- NAVBAR -->
  <div style="height:10px; background:#27aae1;"></div>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand"> KAZUYA.COM</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarcollapseCMS">
        <ul class="navbar-nav mr-auto">

          <li class="nav-item">
            <a href="Blog.php" class="nav-link">Home</a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">About Us</a>
          </li>
          <li class="nav-item">
            <a href="Blog.php" class="nav-link">Blog</a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">Contact Us</a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">Features</a>
          </li>
        </ul>

        <ul class="navbar-nav ml-auto">

          <!-- spサイズではフォーム非表示 -->
          <form action="Blog.php" class="form-inline d-none d-sm-block">
            <div class="form-group">
              <input type="text" name="Search" placeholder="Search here" value="" class="form-control mr-2">
              <button class="btn btn-primary" name="SearchButton">Go</button>
            </div>
          </form>

        </ul>
      </div>
    </div>
  </nav>
  <div style="height:10px; background:#27aae1;"></div>
  <!-- NAVBAR END -->



  <!-- HEADER -->
  <div class="container">
    <div class="row mt-4">

      <!-- Main Area Start -->
      <div class="col-sm-8" style="min-height: 40px; background: grey;">
        <h1>The Complete Responsive CMS Blog</h1>
        <!-- lead classは文字を小さくする -->
        <h1 class="lead">The Complete blog by using PHP by Kazuya</h1>

        <!-- Fetch Posts From DB -->
        <?php
        global $ConnectingDb;

        // SQL query when Search button is active (サーチボタンに入力された時のみ発動)
        if (isset($_GET["SearchButton"])) {

          // inputの name属性を取得
          $Search = $_GET["Search"];

          // :searchはプレースホルダーで入力された値
          // if any of the condition is true, show us the results.
          $sql = 'select * from posts 
          WHERE datetime LIKE :search
          OR title LIKE :search
          OR category LIKE :search
          OR post LIKE :search';
          $stmt = $ConnectingDB->prepare($sql);

          // bindValueでプレースホルダーに値を入れる(セキュリティのため)
          // SQLでLIKEを使っているので、%%で囲む。少しでも一致しているものを表示するようになる
          $stmt->bindValue(':search', '%' . $Search . '%');

          $stmt->execute();
        } else {
          // super global でブラウザバーのURLから値を取得し、//! 記事１ページのみ表示
          $PostIdFromURL = $_GET["id"];

          // ブラウザのURLバーに id= が入ってなかったら、セッションから取得したエラーを表示し一覧のBlog.phpに飛ばす
          if (!isset($PostIdFromURL)) {
            $_SESSION["ErrorMessage"] = "Bad Request !!"; // Blog.php内にエラーが表示される
            Redirect_to("Blog.php");
          }

          $sql = "select * from posts where id = '$PostIdFromURL' ";

          // 指定したSQL文をデータベースに対して発行してくれる役割を持っています。
          // queryメソッドを使用して、sqlをデータベースに届けなければいけないのです。
          // sql文を実行する時は必ずDBにアクセスせなあかん
          $stmt = $ConnectingDB->query($sql);
        }

        // DB のpostsテーブルの各カラムをループで取得
        // fetch()はPDOオブジェクトでDBからデータを取り出した際に「配列の形式を指定できる」ことを指します。 $DataRowsはこのwhile文内でのみ使用可能
        while ($DataRows = $stmt->fetch()) :
          $PostId = $DataRows["id"];
          $DateTime = $DataRows["datetime"];
          $PostTitle = $DataRows["title"];
          $Category = $DataRows["category"];
          $Admin = $DataRows["author"];
          $Image = $DataRows["image"];
          $PostDescription = $DataRows["post"];
        ?>

          <!-- ---------------------------------- -->
          <!-- display the data extracted from DB -->
          <!-- ---------------------------------- -->
          <div class="card">
            <!--  htmlentities は、変換可能な文字を全てHTMLエンティティに変換する関数です。 -->
            <img src="./Uploads/<?php echo htmlentities($Image); ?>" alt="postImg" class="blogImg card-img-top">
            <div class="card-body">
              <h4 class="card-title"><?php echo htmlentities($PostTitle); ?></h4>
              <small class="text-muted">Written by <?php echo htmlentities($Admin); ?> On <?php echo htmlentities($DateTime); ?></small>
              <span class="commentNumber badge badge-dark text-light">Comments 20</span>

              <hr />
              <p class="card-text">
                <?php echo htmlentities($PostDescription); ?>
              </p>
              <!-- DBからIdを取得し、そのIdをURLにくっつける -->
              <a class="postCardBtn" href="FullPost.php?id=<?php echo htmlentities($PostId); ?>">
                <span class="btn btn-info">Read More >> </span>
              </a>
            </div>
          </div>
        <?php endwhile; ?>
        <!-- Card End -->

      </div>
      <!-- Main Area End -->

      <!-- Side Area Start -->
      <div class="col-sm-4" style="min-height: 40px; background: white;">

      </div>
      <!-- Side Area End -->
    </div>
  </div>

  <!-- HEADER END -->






  <br>
  <!-- FOOTER -->
  <footer class="bg-dark text-white">
    <div class="container">
      <div class="row">
        <div class="col">
          <p class="lead text-center">Theme By | Kazuya Kojima | <span id="year"></span> &copy; ----All right Reserved.</p>
          <p class="text-center small"><a style="color: white; text-decoration: none; cursor: pointer;" href="http://jazebakram.com/coupons/" target="_blank"> This site is only used for Study purpose Kazuya.com have all the rights. no one is allow to distribute copies other then <br>&trade; jazebakram.com &trade; Udemy ; &trade; Skillshare ; &trade; StackSkills</a></p>
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