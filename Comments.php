<?php
// Header
include('./templates/AdminHeader.php');
?>

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
    <?php
    echo ErrorMessage();
    echo SuccessMessage();
    ?>

    <div class="row">
      <div class="col-lg-12">





        <!--  --------------->
        <!-- Un-Approved  -->
        <!-- ------ --------->
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
            if (mb_strlen($CommenterName) > 10) {
              $CommenterName = mb_substr($CommenterName, 0, 10) . '...';
            }
            if (mb_strlen($DateTimeOfComment) > 26) {
              $DateTimeOfComment = mb_substr($DateTimeOfComment, 0, 26) . '...';
            }
          ?>

            <!-- HTMLに出力（テーブル内） -->
            <tbody>
              <tr>
                <td><?php echo htmlentities($SrNo); ?></td>
                <td><?php echo htmlentities($CommenterName); ?></td>
                <td><?php echo htmlentities($DateTimeOfComment); ?></td>
                <td><?php echo htmlentities($CommentContent); ?></td>

                <!-- postsテーブルのidとこの$CommentIdはLovebirdの関係で繋がっている  -->
                <td><a class="btn btn-success" href="ApproveComments.php?id=<?php echo $CommentId; ?>">Approve</a></td>

                <td><a class="btn btn-danger" href="DeleteComments.php?id=<?php echo $CommentId; ?>">Delete</a></td>

                <td><a class="btn btn-primary" href="FullPost.php?id=<?php echo $CommentPostId; ?>" target="_blank">Live Preview</a></td>
              </tr>

            </tbody>
          <?php endwhile; ?>
        </table>








        <!--  ------------------->
        <!-- Approved Comments -->
        <!-- ------ ------------->
        <h2>Approved Comments</h2>
        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr>
              <th>No. </th>
              <th>Name</th>
              <th>Date&Time</th>
              <th>Comment</th>
              <th>Revert</th>
              <th>Action</th>
              <th>Details</th>
            </tr>
          </thead>



          <?php
          // 関数内からグローバル変数にアクセスする際に必要
          global $ConnectingDB;

          // statusが OFFのやつのみ を最新順で取得する
          $sql = "select * from comments where status = 'ON' ORDER BY id desc ";
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
            if (mb_strlen($CommenterName) > 10) {
              $CommenterName = mb_substr($CommenterName, 0, 10) . '...';
            }
            if (mb_strlen($DateTimeOfComment) > 26) {
              $DateTimeOfComment = mb_substr($DateTimeOfComment, 0, 26) . '...';
            }
          ?>

            <!-- HTMLに出力（テーブル内） -->
            <tbody>
              <tr>
                <td><?php echo htmlentities($SrNo); ?></td>
                <td><?php echo htmlentities($CommenterName); ?></td>
                <td><?php echo htmlentities($DateTimeOfComment); ?></td>
                <td><?php echo htmlentities($CommentContent); ?></td>

                <!-- postsテーブルのidとこの$CommentIdはLovebirdの関係で繋がっている  -->
                <td><a class="btn btn-warning" href="DisApproveComments.php?id=<?php echo $CommentId; ?>">Dis-Approve</a></td>

                <td><a class="btn btn-danger" href="DeleteComments.php?id=<?php echo $CommentId; ?>">Delete</a></td>

                <td><a class="btn btn-primary" href="FullPost.php?id=<?php echo $CommentPostId; ?>" target="_blank">Live Preview</a></td>
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