<?php
// Header
include('./templates/AdminHeader.php');
?>


<?php
 // Login.phpで設定したセッションを使い格納(ログインの有無を判断)
$Admin = $_SESSION["UserName"];?>

<!--  -------->
<!-- Menu ---->
<!-- ------ -->
<div class="bg-dark text-white py-3">
  <div class="container">
    <div class="row">
      <h1 class="col-8 md-col-6 sm-col-4"><i class="fas fa-cog"></i>Dashboard</h1>
      <span class="fs-5">Welcome
        <span class="text-danger fs-3">
            <?php echo htmlentities($Admin); ?> !!
          </span>
      </span>

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
</div>

<!--  -------->
<!-- Main Area -->
<!-- ------ -->
<section class="container py-2 mb-r">
  <div class="row">

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
      <h1>Top 5 posts</h1>
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
        // $sql = "select * from posts ORDER BY id desc";

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
<?php
include('./templates/Footer.php');
?>