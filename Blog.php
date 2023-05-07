<?php
//Header
include("./templates/Header.php");

// Functions
require_once("includes/Functions.php");
?>

<main class="blogContainer">
  <div class="blogContainerHero">
    <div class="blogContainerHero__item">
      <img src="./src/img/vertical-cat.png" alt="" class="blogContainerHero__img">
    </div>
    <div class="blogContainerHero__item">
      <img src="./src/img/blog_hero.png" alt="" class="blogContainerHero__img">
    </div>
    <div class="blogContainerHero__item">
      <img src="./src/img/verical-cat2.png" alt="" class="blogContainerHero__img">
    </div>
  </div>

  <!-- ------------ ------------->
  <!-- Recent Posts -->
  <!-- ------------ ------------->
  <section class="recentPost">
    <h2 class="recentPost__title">Recent Posts</h2>
    <div class="recentPostContainer">

      <?php
      // postsテーブルから最新の4件取得
      $sql = "select * from posts ORDER BY id desc LIMIT 0, 4 ";
      $stmt = $ConnectingDB->query($sql);

      while ($DataRows = $stmt->fetch()) :
        $Id = $DataRows['id'];
        $Title = $DataRows['title'];
        $DateTime = $DataRows['datetime'];
        $Image = $DataRows['image'];
      ?>

        <div class="recentPostContainer__item">
          <a href="FullPost.php?id=<?php echo htmlspecialchars($Id, ENT_QUOTES); ?>">
            <img src="./Uploads/<?php echo htmlspecialchars($Image, ENT_QUOTES); ?>" alt="" class="d-block img-fluid align-self-start">
            <!-- postsテーブルの各記事のIDのURLに飛ぶ -->
            <div class="recentPostContainer__caption">
              <h6 class="lead"><?php echo htmlspecialchars($Title, ENT_QUOTES); ?></h6>
            </div>
          </a>
        </div>
        <hr />
      <?php endwhile; ?>
    </div>
  </section>

  <?php
  // Display Error Message
  echo ErrorMessage();
  echo SuccessMessage();
  ?>

  <?php
  // ============================
  // Display User searched Value 
  // ============================
  if (isset($_GET['Search'])) {
    $Search = $_GET["Search"];

    echo "<div style='font-size:2rem; background: white; border-radius: 20px; text-align:center;'> Search result:" . ' ' .
      "<span style='font-size: 2.4rem; color:red; letter-spacing: 1px;'> $Search </span>
       </div>";
  }
  ?>

  <!-- ========================== -->
  <!-- All Posts -->
  <!-- =========== ===============-->
  <section class="allPost">
    <h2 class="recentPost__title">All Posts</h2>
    <div class="parent">
      <?php
      // ======================================================
      //  Search button is active  (サーチボタンに入力された時のみ発動)
      // ======================================================
      if (isset($_GET["SearchButton"])) {

        // inputの値を取得
        $Search = $_GET["Search"];

        // :searchはプレースホルダーで入力された値
        // if any of the condition is true, show us the results.
        $sql = 'select * from posts 
          WHERE datetime LIKE :search
          OR title LIKE :search
          OR category LIKE :search
          OR post LIKE :search';
        $stmt = $ConnectingDB->prepare($sql);

        // SQLでLIKEを使っているので、%%で囲む。少しでも一致しているものを表示
        $stmt->bindValue(':search', '%' . $Search . '%');

        $stmt->execute();

        // ======================================================
        // Pagination is Active ex) Blog.php?page=1 (9記事ずつ表示する計算)
        // ======================================================
      } elseif (isset($_GET["page"])) {
        $Page = $_GET["page"];
        // if user press page = 0
        if ($Page == 0 || $Page < 1) {
          $Page = 1;
        } else {
          $ShowPostFrom = ($Page * 9) - 9;
        }

        $ShowPostFrom = ($Page * 9) - 9; // 0~8, 9~17 ... 

        $sql = "select * from posts ORDER BY id desc LIMIT $ShowPostFrom, 9";
        $stmt = $ConnectingDB->query($sql);

        // =============================================================
        //  Category is active in URL Tab
        // ========================================================
      } elseif (isset($_GET["category"])) {
        $Category = $_GET["category"];

        // プレースホルダーの :catを使いbindValueで実際の値を入れ込む
        $sql = "select * from posts WHERE category= :cat";

        $stmt = $ConnectingDB->prepare($sql);
        $stmt->bindValue(":cat", $Category);
        $stmt->execute();
      }
      // ==============================================================
      // The default Page (blog記事を一覧表示) (どんなURLを入力してもこれが実行)
      // ==============================================================
      else {
        if (empty($_GET[''])) {

          // 9件表示で、ページネーションあり
          if (0 == 0 || 9 < 1) {
            $Page = 1;
          } else {
            $ShowPostFrom = (1 * 9) - 9; 
          }
        }
        $ShowPostFrom = (1 * 9) - 9; // 0~8, 9~17 ...

        $sql = "select * from posts ORDER BY id desc LIMIT $ShowPostFrom, 9";
        $stmt = $ConnectingDB->query($sql);
      }

      // DB のpostsテーブルの各カラムをループで取得
      // fetch()はPDOオブジェクトでDBからデータを取り出した際に「配列の形式を指定できる
      while ($DataRows = $stmt->fetch()) :
        $PostId = $DataRows["id"];
        $DateTime = $DataRows["datetime"];
        $PostTitle = $DataRows["title"];
        $Category = $DataRows["category"];
        $Admin = $DataRows["author"];
        $Image = $DataRows["image"];
        $PostDescription = $DataRows["post"];
      ?>

        <!-- ------------ ------------->
        <!-- All Posts HTML-->
        <!-- ------------ ------------->
        <a class="gridItem" href="FullPost.php?id=<?php echo htmlentities($PostId); ?>">
          <img src="./Uploads/<?php echo htmlentities($Image); ?>" alt="Post Image" class="blogImg card-img-top">
          <div class="allPostCaption">
            <h4 class="card-title"><?php echo htmlentities($PostTitle); ?></h4>
            <p class="">Category: <?php echo htmlspecialchars($Category); ?></p>
          </div>
        </a>
      <?php endwhile; ?>
    </div>
  </section>

  <!--  --------------------------------------------->
  <!-- pagination -->
  <!-- ------------------------------------------- -->
  <section>
    <ul class="pagination pagination-lg">

      <!-- Backward Button -->
      <!-- もし現在のページが、2ページ以上なら下記HTMLを表示 -->
      <?php if (isset($Page)) :
        if ($Page > 1) : ?>
          <li class="page-item">
            <a href="Blog.php?page=<?php echo $Page - 1; ?>" class="page-link">&laquo;</a>
          </li>
        <?php endif; ?>
      <?php endif; ?>

      <!-- Pagination  ----------------------->
      <?php
      // COUNT(*)はテーブル内の全ての数を返す
      $sql = "select COUNT(*) FROM posts";
      $stmt = $ConnectingDB->query($sql);
      $RowPagination = $stmt->fetch();

      //array_shift() は、array の最初の値を取り出して返す。配列は、要素一つ分だけ短くなり、全ての要素は前にずれます。 
      $TotalPosts = array_shift($RowPagination);
      // echo $TotalPosts . "<br>"; // array9 (全post数)

      // $PostPagination = $TotalPosts / 4; // 2.25  (9 / 4 = 2.25)
      $PostPagination = $TotalPosts / 9; // 2.25  (9 / 4 = 2.25)

      // 小数点を繰り上げ
      $PostPagination = ceil($PostPagination);
      // echo $PostPagination; // 3

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
  </section>


  <section class="catsPng">
    <img src="./src/img/croppedCat2.png" alt="" class="catsPng__img">
    <img src="./src/img/croppedCat1.png" alt="" class="catsPng__img">
    <img src="./src/img/croppedCat3.png" alt="" class="catsPng__img">
  </section>

  

</main>
<?php
// Footer 
include("./templates/Footer.php") ?>