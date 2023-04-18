<?php
//Header
include("./templates/Header.php");
?>



<!-- Contents -->
<div class="container">
  <div class="row mt-4">

    <!-- Main Area Start -->
    <div class="col-sm-8" style="min-height: 40px; background: grey;">
      <h1>The Complete Responsive CMS Blog</h1>

      <?php
      // inputの name属性を取得
      $Search = $_GET["Search"];
      echo  "Search result of:" . ' ' .  "<span style='font-size: 2rem; color:red; letter-spacing: 1px;'> $Search </span>";
      ?>


      <?php
      // Session.phpから取得したエラーを表示する。ここでechoしとかないと、FullPost.phpで起こしたエラーが表示されない
      echo ErrorMessage();
      echo SuccessMessage();
      ?>

      <!-- Fetch Posts From DB --> 
      <?php
      global $ConnectingDb;

      // ===================================================================
      // SQL query when Search button is active (サーチボタンに入力された時のみ発動)
      // ===================================================================
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

        // ===================================================================
        // when Pagination is Active ex) Blog.php?page=1 (4記事ずつ表示する計算)
        // ===================================================================
      } elseif (isset($_GET["page"])) {
        $Page = $_GET["page"];
        // if user press page = 0
        if ($Page == 0 || $Page < 1) {
          $Page = 1;
        } else {
          $ShowPostFrom = ($Page * 4) - 4;
        }

        $ShowPostFrom = ($Page * 4) - 4; // 0~3, 4~7, 8~11 ... 

        $sql = "select * from posts ORDER BY id desc LIMIT $ShowPostFrom, 4";
        $stmt = $ConnectingDB->query($sql);

        // =====================================================================
        // Query when Category is active in URL Tab
        // =====================================================================
      } elseif (isset($_GET["category"])) {
        $Category = $_GET["category"];

        // プレースホルダーの :catを使いbindValueで実際の値を入れ込む
        $sql = "select * from posts WHERE category= :cat";

        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(":cat", $Category);
        $stmt->execute();
      }

      // =====================================================================
      // The default SQL query (blog記事を一覧表示) (どんなURLを入力してもこれが実行)
      // =====================================================================
      else {
        // 新着順で表示
        $sql = "select * from posts ORDER BY id desc LIMIT 0, 3";

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

            <!-- ------------------------ -->
            <!-- Dis-Approveしたコメント数を表示 -->
            <!-- ------------------------ -->
            <?php
            global $ConnectingDB;

            // commentsテーブル内の Id と一致し、かつ statusが　ON のコメントのみ摘出
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
              <?php
              // 文字制限をかける。　strlen()は文字のバイト数を返す
              if (strlen($PostDescription) > 150) {

                // substr()は指定した所までを表示する
                $PostDescription = substr($PostDescription, 0, 150) . "...";
              }
              echo htmlentities($PostDescription);
              ?>
            </p>
            <!-- DBからIdを取得し、そのIdをURLにくっつける -->
            <a class="postCardBtn" href="FullPost.php?id=<?php echo htmlentities($PostId); ?>">
              <span class="btn btn-info">Read More >> </span>
            </a>
          </div>
        </div>
      <?php endwhile; ?>
      <!-- Card End -->

      <!--  --------------------------------------------->
      <!-- pagination -->
      <!-- ------------------------------------------- -->
      <nav>
        <ul class="pagination pagination-lg">

          <!-- Backward Button -->
          <?php if (isset($Page)) :

            // もし現在のページが、2ページ以上なら下記HTMLを表示
            if ($Page > 1) : ?>
              <li class="page-item">
                <a href="Blog.php?page=<?php echo $Page - 1; ?>" class="page-link">&laquo;</a>
              </li>
            <?php endif; ?>
          <?php endif; ?>


          <!-- Pagination  ----------------------->
          <?php
          global $ConnectingDB;

          // COUNT(*)はテーブル内の全ての数を返す
          $sql = "select COUNT(*) FROM posts";
          $stmt = $ConnectingDB->query($sql);
          $RowPagination = $stmt->fetch();

          //array_shift() は、array の最初の値を取り出して返します。配列 array は、要素一つ分だけ短くなり、全ての要素は前にずれます。 
          $TotalPosts = array_shift($RowPagination);
          echo $TotalPosts . "<br>"; // array9 (全post数)

          $PostPagination = $TotalPosts / 4; // 2.25  (9 / 4 = 2.25)

          // 小数点を繰り上げ
          $PostPagination = ceil($PostPagination);
          echo $PostPagination; // 3

          // pagination(3)に対してループをかける　３つのページネーションを表示
          for ($i = 1; $i <= $PostPagination; $i++) : ?>

            <!-- もしpage=1などがURLに入ってれば、下記のページネーションを表示 -->
            <?php if (isset($Page)) : ?>

              <!-- 現在開いてるページに対して active クラスを付けている (1 == page=1 ならそのページをactive-->
              <?php if ($i == $Page) : ?>
                <li class="page-item active">
                  <a href="Blog.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                </li>

                <!-- 現在開いてるページ以外は activeクラスが付いていない -->
              <?php else : ?>
                <li class="page-item">
                  <a href="Blog.php?page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                </li>

              <?php endif; ?>
            <?php endif; ?>
          <?php endfor; ?>

          <!--  -------------------->
          <!-- Forward Button -->
          <!--  -------------------->
          <?php if (isset($Page)) :

            // もし現在のページが、総ページネーション数より少ない場合のみ下記HTMLを表示する
            if ($Page + 1 <= $PostPagination) : ?>
              <li class="page-item">
                <a href="Blog.php?page=<?php echo $Page + 1 ?>" class="page-link">&raquo;</a>
              </li>
            <?php endif; ?>
          <?php endif; ?>
        </ul>
      </nav>

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

<!-- Contents END -->






<br>


<?php
// Footer
include("./templates/Footer.php")
?>