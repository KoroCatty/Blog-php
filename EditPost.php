<?php
// Header
include('./templates/AdminHeader.php');
?>

<?php
// URL bar ex) (id=1)などを取得
$SearchQueryParameter = $_GET["id"]; ?>

<?php
if (isset($_POST["Submit"])) {

  // Title フォームの値を連想配列で格納
  $PostTitle = $_POST["PostTitle"];

  // Category 
  $Category = $_POST["Category"];

  // Image File (imageには$_POST[]が使えない (DBにはimg名のみ登録 )
  $Image = $_FILES["Image"]["name"];

  // 実際のImage Fileの保存先を指定する
  // PHPの basename関数 は絶対パス、or 相対パスで書かれたパス名からファイル名のみを抜き出す関数
  $Target = "./Uploads/" . basename($_FILES["Image"]["name"]);

  // Textarea
  $PostText = $_POST["PostDescription"]; //フォームの値を連想配列で格納

  $Admin = "Kazuya";

  // Functions.phpで作成した、現在時刻を取得する関数を格納
  $DateTime = getTime();


  // エラー時
  if (empty($PostTitle)) {
    // session.php で定義したセッションにエラーメッセージを格納
    $_SESSION["ErrorMessage"] = "Title can't be empty";

    // Limiting number of characters
  } elseif (strlen($PostText) > 999) { //strlen — 文字列の長さを得る
    $_SESSION["ErrorMessage"] = "Post desc should be less than 1000 characters";

    // 成功時
  } else {
    $ConnectingDB = dbConnect();

    // さらに　image　のバリデーション
    if (!empty($_FILES["Image"]["name"])) {

      // 記事をアップデートの際、ブラウザーのURLバーから id を受け取り、WHEREで、その記事を特定する
      $sql = "UPDATE posts 
    SET title = '$PostTitle', category = '$Category', image='$Image', post='$PostText' 
    WHERE id='$SearchQueryParameter'
    ";

      // image 失敗時
    } else {
      $sql = "UPDATE posts 
    SET title = '$PostTitle', category = '$Category', post='$PostText' 
    WHERE id='$SearchQueryParameter'
    ";
    }

    // 上で定義したSQL文を実行 (いつもDBに繋がないといけない)
    $Execute = $ConnectingDB->query($sql);

    // 実際のimageをフォルダーに格納させる関数 (PHPはimgを tmp_name というフォルダに仮置きしてるので、それを自分で指定した先に持ってくる($Targetに) )
    move_uploaded_file($_FILES["Image"]["tmp_name"], $Target);

    // DBとやり取りするときはエラーが起きやすいのでIF文使用
    if ($Execute) {
      // lastInsertID()関数は、AUTO_INCREMENT機能を使用しているテーブルで新しいレコードを挿入すると、そのテーブルの主キーとして自動的に生成された値を取得するために、lastInsertID()メソッドを使用
      $_SESSION["SuccessMessage"] = "Post has been updated Successfully!";
      Redirect_to("Posts.php");
    } else {
      $_SESSION["ErrorMessage"] = "Something went wrong!";
      Redirect_to("Posts.php");
    }
  }
}
?>

<!--  -------->
<!-- header -->
<!-- ------ -->
<header class="bg-dark text-white py-3">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1 class=""><i class="fas fa-edit"></i>Edit Post</h1>
      </div>
    </div>
  </div>
</header>

<!--  -------->
<!-- Main Area -->
<!-- ------ -->
<section class="container py-2 mb-4 mainAreaCat">
  <div class="row categoryMain">
    <div class="offset-lg-1 col-lg-10 categoryMain__item">
      <?php
      // ここでフォーム送信時にどちらかを表示させる
      echo ErrorMessage();
      echo SuccessMessage();

      // =========================================
      // Fetching Existing Content to edit easily
      // =========================================
      // Get URL parameter from URL Bar
      // $SearchQueryParameter = $_GET["id"];  // id=8 などを取得

      // SQL文でデータをDBから取得
      $sql = "select * from posts where id = '$SearchQueryParameter' ";
      $stmt = $ConnectingDB->query($sql);

      // postsテーブルから各カラムを取得　そしてこのデータをHTMLに注入し、editしやすくする
      while ($DataRows = $stmt->fetch()) {
        $TitleToBeUpdated = $DataRows["title"];
        $CategoryToBeUpdated = $DataRows["category"];
        $ImageToBeUpdated = $DataRows["image"];
        $PostToBeUpdated = $DataRows["post"];
      }
      ?>

      <!-- enctype save the image to the folder wherever you want  -->
      <!-- enctype属性は、フォームの送信データのMIMEタイプを設定するための属性です。 属性値にMIMEタイプを示す文字列を指定することで、フォームのデータが送信される際に用いられるMIMEタイプを設定することができます。 -->
      <form action="EditPost.php?id=<?php echo htmlspecialchars($SearchQueryParameter, ENT_QUOTES); ?>" class="" method="post" enctype="multipart/form-data">
        <div class="card mb-3">

          <div class="card-body bg-dark">

            <!-- ----------------------- -->
            <!-- Title  -->
            <!-- ----------------------- -->
            <div class="form-group">
              <label for="title">
                <span class="FieldInfo">
                  Category Title:
                </span>
              </label>

              <!-- Display the current text in DB -->
              <input class="form-control" type="text" name="PostTitle" id="title" placeholder="Type title here" value="<?php echo htmlspecialchars($TitleToBeUpdated, ENT_QUOTES); ?>">
            </div>

            <!-- ----------------------- -->
            <!-- Category (selection)-->
            <!-- ----------------------- -->
            <div class="form-group">
              <span class="FieldInfo">Current Category: </span>

              <!-- DBにある現在のカテゴリーを表示 -->
              <span class="existCategory"><?php echo htmlspecialchars($CategoryToBeUpdated, ENT_QUOTES); ?></span>
              <br />
              <label for="CategoryTitle">
                <span class="FieldInfo">
                  Choose Category:
                </span>
              </label>
              <!-- fetch all category from DB -->
              <select name="Category" id="CategoryTitle" class="form-control">

                <?php
                // DBから二つのカラムを取得
                $sql = "select id, title from category";

                //指定したSQL文をデータベースに対して発行してくれる役割
                $stmt = $ConnectingDB->query($sql);

                // fetch every single category (PDOでデータベースからデータを取り出した際の「配列の形式を指定するモード」のこと)
                while ($DateRows = $stmt->fetch()) : ?>
                  <?php
                  // 連想配列で撮ってきて格納
                  $Id = $DateRows["id"];
                  $CategoryName = $DateRows["title"];
                  ?>

                  <!-- ループでDBのcategoryテーブルにある Column を表示する -->
                  <option class=""><?php echo htmlspecialchars($CategoryName, ENT_QUOTES); ?></option>

                <?php endwhile; ?>
              </select>
            </div>


            <!-- ----------------------- -->
            <!-- Image File  -->
            <!-- ----------------------- -->
            <div class="form-group my-4" style="color: white;">
              <span class="FieldInfo">Current Image: </span>

              <!-- DBにある現在のimageを自分のフォルダから表示 -->
              <img src="./Uploads/<?php echo htmlspecialchars($ImageToBeUpdated, ENT_QUOTES); ?>" alt="" class="editImg mb-1">

              <div class="form-group">
                <label for="Post" class="custom-file-label">Select Image</label>
                <div class="custom-file">
                  <input type="File" name="Image" id="imageSelect" value="" class="custom-file-input">
                </div>
              </div>
            </div>
            <!-- ----------------------- -->
            <!-- POST Content(本文) -->
            <!-- ----------------------- -->
            <div class="form-group">
              <label for="Post">
                <span class="FieldInfo">
                  Post:
                </span>
              </label>
              <!-- DBにある現在の本文を表示 -->
              <textarea class="form-control" name="PostDescription" id="Post" cols="30" rows="10"><?php echo htmlspecialchars($PostToBeUpdated, ENT_QUOTES); ?></textarea>
            </div>

            <br />
            <hr />

            <!-- Buttons -->
            <div class="row">
              <div class="col-lg-6 mb-2">
                <a href="Dashboard.php" class="btn btn-warning">
                  <i class="fas fa-arrow-left"></i>Back To Dashboard
                </a>
              </div>
              <div class="col-lg-6 mb-2">
                <button class="btn btn-success btn-block" type="submit" name="Submit">
                  <i class="fas fa-check"></i>Update
                </button>
              </div>
            </div>
          </div>

        </div>
      </form>
    </div>
  </div>
</section>










<!--  -------->
<!-- Footer -->
<!-- ------ -->
<?php
include('./templates/Footer.php');
?>