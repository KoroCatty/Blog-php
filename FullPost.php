<?php
// DB connection
include_once("./DB/connect.php");
$ConnectingDB = dbConnect();

// Functions
require_once("Includes/Functions.php");

// Sessions
require_once("Includes/Sessions.php");

// Get the parameter from Browser URL Bar
$SearchQueryParameter = $_GET["id"];
?>


<?php
// インプットタグの name="Submit" ボタンから取得し、送信されたらこのif文を発火
if (isset($_POST["Submit"])) {

  // 各フォームから取得
  $Name = $_POST["CommenterName"]; // フォームの値を格納
  $Email = $_POST["CommenterEmail"];
  $Comment = $_POST["CommenterThoughts"];

  // Functions.phpで作成した、現在時刻を取得する関数を格納
  $DateTime = getTime();

  // エラー時 (各フォームの空を許さない。どれか一つでも空ならエラー)
  if (empty($Name) || empty($Email) || empty($Comment)) {
    $_SESSION["ErrorMessage"] = "All fields must be filled out"; //エラーメッセをセッションに

    // Functions.phpで定義しているので、ここで指定した先にリダイレクトできるようになる
    Redirect_to("FullPost.php?id=$SearchQueryParameter"); // in order to stay on the same page

  } elseif (strlen($Comment) > 500) { //strlen — 文字列の長さを得る
    $_SESSION["ErrorMessage"] = "Comment should be less than 500 characters";
    Redirect_to("FullPost.php?id=$SearchQueryParameter");

    // 成功時
    // Query to insert comment in DB when everything is fine
  } else {
    // 上記のValidationをスルーしたのでDBに値を入れていく ( approvedby などはユーザーに入力されない所なので、sqlインジェクション対策のbindValueをしなくても良い。) post_idはpostsテーブルのPrimary keyと繋がっている
    $sql = "insert into comments(datetime, name, email, comment, approvedby, status, post_id)";

    // This is dummy (プレースホルダー。SQLインジェクション対策)
    $sql = $sql . "values(:datetime,:name,:email,:comment, 'Pending', 'OFF', :postIdFromURL)";

    // connect.phpから取得した関数を格納 (sql文を実行する際に必要)
    $ConnectingDB = dbConnect();

    $stmt = $ConnectingDB->prepare($sql); // sql文は prepare()を通す必要がある

    //  bindValueは,対応する名前あるいは疑問符のプレースホルダに値をバインドする
    $stmt->bindValue(':datetime', $DateTime); // 1.dummy, 2,実際の値
    $stmt->bindValue(':name', $Name);
    $stmt->bindValue(':email', $Email);
    $stmt->bindValue(':comment', $Comment);
    $stmt->bindValue(':postIdFromURL', $SearchQueryParameter); // URLのid番号に応じてここが変わる。 (1とか２とかが入る)

    // var_dump($stmt); // Debugging

    // 実行するコードを格納
    $Execute = $stmt->execute();

    // var_dump($Execute);

    // DBとやり取りするときはエラーが起きやすいのでIF文使÷用
    if ($Execute) {
      //  成功時
      $_SESSION["SuccessMessage"] = "Comment submitted successfully";
      Redirect_to("FullPost.php?id=$SearchQueryParameter"); // stay on the same page
      // 失敗時
    } else {
      $_SESSION["ErrorMessage"] = "Something went wrong!";
      Redirect_to("FullPost.php?id=$SearchQueryParameter");
    }
  }
}
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

  <title>Full Post Page</title>
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
        <?php
        echo ErrorMessage();
        echo SuccessMessage();
        ?>

        <!-- ------------------- -->
        <!-- Fetch Posts From DB -->
        <!-- ------------------- -->
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
              <small class="text-muted">Category: <?php echo htmlspecialchars($Category); ?> & Written by <?php echo htmlentities($Admin); ?> On <?php echo htmlentities($DateTime); ?></small>
              <!-- <span class="commentNumber badge badge-dark text-light">Comments 20</span> -->

              <!-- ------------------------ -->
              <!-- Dis-Approveしたコメント数を表示 -->
              <!-- ------------------------ -->
              <?php
              global $ConnectingDB;

              // commentsテーブル内の Id と一致し、かつ statusが　ON のコメントのみ摘出
              // posts table id <==> comments table post_id are connected like love birds
              $sqlDisApprove = "select COUNT(*) from comments WHERE post_id = '$PostId' AND status = 'OFF' ";

              $stmtDisApprove = $ConnectingDB->query($sqlDisApprove);
              $RowsTotal = $stmtDisApprove->fetch();

              // fetch()はarrayで帰ってくるので、それをstringに変換する関数
              $Total = array_shift($RowsTotal);

              // if the comment is 0, do not show the number
              if ($Total > 0) {
                echo  "<span class='commentNumber badge badge-dark text-light'>Comments $Total</span>";
              }
              ?>


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

        <!-- ------------- -->
        <!-- Comment 　    -->
        <!-- ------------- -->
        <!-- fetching existing Comment From DB -->
        <span class="FieldInfo">Comments</span>
        <br />
        <br />
        <?php
        $sql = "select * from comments 
        WHERE post_id = '$SearchQueryParameter' AND status = 'ON'"; // ONにコメントだけ表示

        $stmt = $ConnectingDB->query($sql); // DB接続して取得
        while ($DataRows = $stmt->fetch()) : // fetchでDBのカラムを取り出す(必要なカラムのみ)
          $CommentDate = $DataRows['datetime'];
          $CommenterName = $DataRows['name'];
          $CommentContent = $DataRows['comment'];

        ?>

          <!-- display comments that are extracted by DB -->
          <div class="commentBlock media">
            <img src="./src/img/Koro.jpg" alt="" class="commentImg d-block img-fluid align-self-center">
            <div class="media-body ml-2">
              <h3 class=""><?php echo htmlentities($CommenterName); ?></h3>
              <p class="small"><?php echo htmlentities($CommentDate); ?></p>
              <p class=""><?php echo htmlentities($CommentContent); ?></p>
            </div>
          </div>
          <hr />
        <?php endwhile; ?>





        <!-- Comment From -->
        <div class="">
          <form action="./FullPost.php?id=<?php echo htmlentities($SearchQueryParameter) ?>" method="post">
            <div class="card mb-3">
              <div class="card-header">
                <h5 class="FieldInfo">Share your thought</h5>
              </div>
              <div class="card-body">

                <!-- Name -->
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span>
                    </div>
                    <input name="CommenterName" placeholder="Your Name" type="text" class="form-control" value="">
                  </div>
                </div>

                <!-- Email -->
                <div class="form-group">
                  <div class="input-group">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input name="CommenterEmail" placeholder="Your Email" type="email" class="form-control" value="">
                  </div>
                </div>

                <!-- 本文 -->
                <div class="form-group">
                  <textarea name="CommenterThoughts" cols="30" rows="6" class="form-control"></textarea>
                </div>

                <!-- Submit button -->
                <button class="btn btn-primary" name="Submit" type="submit">Submit</button>
              </div>
            </div>

          </form>
        </div>



      </div>
      <!-- Main Area End -->


      <!-- --------------------------- -->
      <!-- Side Area Start -->
      <!-- --------------------------- -->
      <div class="col-sm-4" style="min-height: 40px; background: white;">
        <div class="card mt-4">
          <div class="card-body">
            <img src="./src/img/Koro.jpg" alt="" class="d-block img-fluid mb-3">
            <div class="text-center">
              Lorem ipsum dolor sit amet, consectetur adipisicing elit. Enim qui, sequi autem officiis iste quidem quo omnis placeat itaque id. Eum est, consequatur nesciunt ea explicabo aperiam quod illo esse.
            </div>
          </div>
        </div>
        <br />
        <div class="card">
          <div class="card-header bg-dark text-light">
            <h2 class="lead">Sign Up !</h2>
          </div>

          <div class="card-body">
            <button class="btn btn-success btn-block text-center text-white" name="button">Join The Forum</button>
            <button class="btn btn-danger btn-block text-center text-white mb-4" name="button">Login</button>
            <div class="input-group mb-3">
              <input type="text" class="form-control" name="" placeholder="Enter your Email" value="">

              <div class="input-group-append">
                <button class="btn btn-primary btn-block text-center text-white" name="button">Subscribe Now</button>
              </div>
            </div>
          </div>
        </div>
        <br>

        <div class="card">
          <div class="card-header bg-primary text-light">
            <h2 class="lead">Categories</h2>
          </div>
          <div class="card-body">
            <?php
            // カテゴリーを全て取得して表示
            global $ConnectingDB;
            $sql = "select * from category ORDER BY id desc";
            $stmt = $ConnectingDB->query($sql);
            while ($DataRows = $stmt->fetch()) :
              $CategoryId = $DataRows["id"];
              $CategoryName = $DataRows["title"];
            ?>

              <!-- category title名を　URLに付与したページに飛ばす -->
              <a href="Blog.php?category=<?php echo $CategoryName; ?>">
                <span class="heading"><?php echo $CategoryName; ?></span><br />
              </a>
            <?php endwhile; ?>
          </div>
        </div>
        <br />

        <!--  --------------------->
        <!-- Recent Posts -->
        <!--    ------------------->
        <div class="card">
          <div class="card-header bg-info text-white">
            <h2 class="lead">Recent Posts</h2>
          </div>

          <div class="card-body">
            <?php

            // postsテーブルから5件取得
            global $ConnectingDB;
            $sql = "select * from posts ORDER BY id desc LIMIT 0, 5 ";
            $stmt = $ConnectingDB->query($sql);

            while ($DataRows = $stmt->fetch()) :
              $Id = $DataRows['id'];
              $Title = $DataRows['title'];
              $DateTime = $DataRows['datetime'];
              $Image = $DataRows['image'];
            ?>

              <div class="media">
                <img src="./Uploads/<?php echo htmlentities($Image); ?>" alt="" class="d-block img-fluid align-self-start" width="72" height="76">

                <!-- postsテーブルの各記事のIDのURLに飛ぶ -->
                <div class="media-body ml-2">
                  <a href="FullPost.php?id=<?php echo htmlentities($Id); ?>" target="_blank">
                    <h6 class="lead"><?php echo htmlentities($Title); ?></h6>
                  </a>
                  <p class="small"><?php echo htmlentities($DateTime); ?></p>
                </div>
              </div>
              <hr />
            <?php endwhile; ?>

          </div>
        </div>

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