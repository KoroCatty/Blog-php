<?php
// Header
include('./templates/AdminHeader.php');
?>



  <div class="bg-dark text-white py-3">
    <div class="container">
      <div class="row">
      <h1 class=""><i class="fas fa-edit"></i>Blog Posts</h1>
      </div>
    </div>
  </div>




  <!--  ------- -->
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



                <td>
                  <!-- ------------------------ -->
                  <!-- Approveしたコメント数を表示 -->
                  <!-- ------------------------ -->
                  <span class="badge badge-success">
                    <?php
                    global $ConnectingDB;

                    // commentsテーブル内の Id と一致し、かつ statusが　ON のコメントのみ摘出
                    $sqlApprove = "select COUNT(*) from comments WHERE post_id = '$Id' AND status = 'ON' ";

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

                    // commentsテーブル内の Id と一致し、かつ statusが　ON のコメントのみ摘出
                    $sqlDisApprove = "select COUNT(*) from comments WHERE post_id = '$Id' AND status = 'OFF' ";

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
<?php
include('./templates/Footer.php');
?>