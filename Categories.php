<?php
// Header
include('./templates/AdminHeader.php');
?>

<?php
if (isset($_POST["Submit"])) {

  // フォームの値を格納
  $Category = $_POST["CategoryTitle"];

  // Login.php で設定したセッションを格納
  $Admin = $_SESSION["UserName"];

  // Functions.phpで作成した、現在時刻を取得する関数を格納
  $DateTime = getTime();

  // エラー時
  if (empty($Category)) {

    // session.phpで定義　
    $_SESSION["ErrorMessage"] = "All fields must be filled out";

    // Functions.phpで定義
    Redirect_to("Categories.php");
  } elseif (strlen($Category) > 49) { //strlen — 文字列の長さを得る
    $_SESSION["ErrorMessage"] = "Title should be less than 50 characters";
    Redirect_to("Categories.php");

    // 成功時
  } else {
    // 上記のValidationをスルーしたのでDBに値を入れていく
    $sql = "insert into category(title, author, datetime)";

    // This is dummy (プレースホルダー。SQLインジェクション対策)
    $sql = $sql . "values(:categoryName, :adminName, :dateTime)";

    // connect.phpから取得した関数を格納 (sql文を実行する際に必要)
    $ConnectingDB = dbConnect();

    $stmt = $ConnectingDB->prepare($sql); // sql文は prepare()を通す必要がある

    //  bindValueは,対応する名前あるいは疑問符のプレースホルダに値をバインドする
    $stmt->bindValue(':categoryName', $Category); // 1.dummy, 2,実際の値
    $stmt->bindValue(':adminName', $Admin); // 1.dummy, 2,実際の値
    $stmt->bindValue('dateTime', $DateTime); // 1.dummy, 2,実際の値

    // 実行するコードを格納
    $Execute = $stmt->execute();

    // DBとやり取りするときはエラーが起きやすいのでIF文使用
    if ($Execute) {
      // 一番最後のIDを表示させる lastInsertID()関数をDBを通して実行
      $_SESSION["SuccessMessage"] = "Category No.: " . $ConnectingDB->lastInsertID() . "  " . "added Successfully!!";
    } else {
      $_SESSION["ErrorMessage"] = "Something went wrong!";
      Redirect_to("Categories.php");
    }
  }
}
?>

<!--  -------->
<!-- title -->
<!-- ------ -->
<div class="bg-dark text-white py-3">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1 class=""><i class="fas fa-edit"></i>Mange Categories</h1>
      </div>
    </div>
  </div>
</div>

<!--  ----------->
<!-- Main Area -->
<!-- ------ ----->
<section class="container py-2 mb-4 mainAreaCat">
  <div class="row categoryMain">
    <div class="offset-lg-1 col-lg-10 categoryMain__item">
      <?php
      // ここでフォーム送信時にどちらかを表示させる
      echo ErrorMessage();
      echo SuccessMessage();
      ?>
      <form action="Categories.php" class="" method="post">
        <div class="card mb-3">
          <div class="card-header">
            <h1 class="">Add New Category</h1>
          </div>
          <div class="card-body bg-dark">
            <div class="form-group">
              <label for="title">
                <span class="FieldInfo">
                  Category Title:
                </span>
              </label>
              <p class="text-white">Type less than 50 characters</p>
              <input class="form-control" type="text" name="CategoryTitle" id="title" placeholder="Type title here" value="">
            </div>
            <div class="row">
              <div class="col-lg-6 mb-2">
                <a href="Dashboard.php" class="btn btn-warning">
                  <i class="fas fa-arrow-left"></i>Back To Dashboard
                </a>
              </div>
              <div class="col-lg-6 mb-2">
                <button class="btn btn-success btn-block" type="submit" name="Submit">
                  <i class="fas fa-check"></i>Publish
                </button>
              </div>
            </div>
          </div>
        </div>
      </form>

      <!--  --------------------->
      <!-- Existing Categories -->
      <!---------------------  -->
      <h2>Existing Categories</h2>
      <table class="table table-striped table-hover">
        <thead class="thead-dark">
          <tr>
            <th>No. </th>
            <th>Date&Time</th>
            <th>Category Name</th>
            <th>Creator Name</th>
            <th>Action</th>
          </tr>
        </thead>
        <?php
        // statusが OFFのものだけを最新順で取得する
        $sql = "select * from category  ORDER BY id desc ";
        $Execute = $ConnectingDB->query($sql);

        // HTMLに表示した時に番号を振り付ける　ex) 1 2 3 4 5 
        $SrNo = 0;
        // DB のカラムをループで取り出す
        while ($DataRows = $Execute->fetch()) :
          $CategoryId = $DataRows["id"];
          $CategoryDate = $DataRows["datetime"];
          $CategoryName = $DataRows["title"];
          $CreatorName = $DataRows["author"];
          $SrNo++; // increment
        ?>

          <!-- HTMLに出力（テーブル内） -->
          <tbody>
            <tr>
              <td><?php echo htmlspecialchars($SrNo, ENT_QUOTES); ?></td>
              <td><?php echo htmlspecialchars($CategoryDate, ENT_QUOTES); ?></td>
              <td><?php echo htmlspecialchars($CategoryName, ENT_QUOTES); ?></td>
              <td><?php echo htmlspecialchars($CreatorName, ENT_QUOTES); ?></td>
              <!-- Delete btn -->
              <td><a class="btn btn-danger" href="DeleteCategory.php?id=<?php echo htmlspecialchars($CategoryId, ENT_QUOTES); ?>">Delete</a></td>
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