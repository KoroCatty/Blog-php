<?php
// Header
include('./templates/AdminHeader.php');
?>

<?php
// URL bar (id=  1 )などを取得
$SearchQueryParameter = $_GET["id"];
?>

<?php
// Validation
// from name="Submit" ボタンから取得
if (isset($_POST["Submit"])) {

  // Title
  $PostTitle = $_POST["PostTitle"]; // フォームの値を連想配列で格納

  // Category 
  $Category = $_POST["Category"]; // フォームの値を連想配列で格納

  // Image File (imageには$_POST[]が使えない (DBにはimg名のみ登録 name of Image　の意味) )
  $Image = $_FILES["Image"]["name"];

  // 実際のImage Fileの保存先を指定する
  // PHPのbasename関数は絶対パス、or 相対パスで書かれたパス名からファイル名のみを抜き出す関数です。
  $Target = "./Uploads/" . basename($_FILES["Image"]["name"]);

  // Textarea (Post Content)
  $PostText = $_POST["PostDescription"]; //フォームの値を連想配列で格納

  $Admin = "Kazuya";



  // Functions.phpで作成した、現在時刻を取得する関数を格納
  $DateTime = getTime();


  // エラー時
  if (empty($PostTitle)) {
    $_SESSION["ErrorMessage"] = "Title can't be empty"; //エラーメッセをセッションに

    // Functions.phpで定義しているので、ここで指定した先にリダイレクトできるようになる
    Redirect_to("EditPost.php?id=$SearchQueryParameter");
  }
  //  elseif (strlen($Category) > 5) { //strlen — 文字列の長さを得る
  //   $_SESSION["ErrorMessage"] = "Post title should be greater than 5 characters";
  //   Redirect_to("EditPost.php?id=$SearchQueryParameter");
  // }
  elseif (strlen($PostText) > 999) { //strlen — 文字列の長さを得る
    $_SESSION["ErrorMessage"] = "Post desc should be less than 1000 characters";
    Redirect_to("EditPost.php?id=$SearchQueryParameter");

    // 成功時
  } else {
    // connect.phpから取得した関数を格納 (sql文を実行する際に必要)
    // Query to Update Post in DB when everything is fine
    $ConnectingDB = dbConnect();

    // さらに　image　のバリデーション
    if (!empty($_FILES["Image"]["name"])) {
      // 内容をアップデート
      // 特定の記事をアップデートしたいので、ブラウザーのURLバーから id を受け取り、WHEREで、その記事を特定する
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

    // var_dump($Execute); // Debugging

    // DBとやり取りするときはエラーが起きやすいのでIF文使用
    if ($Execute) {
      // 一番最後のIDを表示させる lastInsertID()関数をDBを通して実行
      $_SESSION["SuccessMessage"] = "あなたのPost with id : " . $ConnectingDB->lastInsertID() . "Updated Successfully!!!!!!";

      // echo SuccessMessage();

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
        global $ConnectingDB;

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
        <form action="EditPost.php?id=<?php echo htmlentities($SearchQueryParameter); ?>" class="" method="post" enctype="multipart/form-data">
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
                <input class="form-control" type="text" name="PostTitle" id="title" placeholder="Type title here" value="<?php echo htmlentities($TitleToBeUpdated); ?>">
              </div>

              <!-- ----------------------- -->
              <!-- Category (selection)-->
              <!-- ----------------------- -->
              <div class="form-group">
                <span class="FieldInfo">Current Category: </span>

                <!-- DBにある現在のカテゴリーを表示 -->
                <span class="existCategory"><?php echo htmlentities($CategoryToBeUpdated); ?></span>

                <br />
                <label for="CategoryTitle">
                  <span class="FieldInfo">
                    Choose Category:
                  </span>
                </label>
                <!-- fetch all category from DB -->
                <select name="Category" id="CategoryTitle" class="form-control">

                  <!-- PHPのファイル内の全体で扱うことのできる変数 global -->
                  <?php global $ConnectingDB;


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
                    <option class=""><?php echo $CategoryName; ?></option>

                  <?php endwhile; ?>

                </select>
              </div>


              <!-- ----------------------- -->
              <!-- Image File  -->
              <!-- ----------------------- -->
              <div class="form-group my-4" style="color: white;">
                <span class="FieldInfo">Current Image: </span>

                <!-- DBにある現在のimageを表示 -->
                <img src="./Uploads/<?php echo htmlentities($ImageToBeUpdated); ?>" alt="" class="editImg mb-1">

                <div class="custom-file">
                  <input type="File" name="Image" id="imageSelect" value="" class="custom-file-input">

                  <br />

                  <!-- ----------------------- -->
                  <!-- POST Content(本文) -->
                  <!-- ----------------------- -->
                  <label for="Post">
                    <span class="FieldInfo">
                      Post:
                    </span>
                  </label>

                  <!-- DBにある現在の本文を表示 -->
                  <textarea class="form-control" name="PostDescription" id="Post" cols="30" rows="10"><?php echo htmlentities($PostToBeUpdated); ?></textarea>

                </div>
              </div>

              <div class="form-group">
                <label for="Post" class="custom-file-label">Select Image</label>

              </div>


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