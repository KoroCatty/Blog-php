<?php
// Header
include('./templates/AdminHeader.php');
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
  $Target ="./Uploads/".basename($_FILES["Image"]["name"]);

  // Textarea (Post Content)
  $PostText = $_POST["PostDescription"]; //フォームの値を連想配列で格納

  // $Admin = "Kazuya";
  // Login.phpで設定したセッションを使い格納
  $Admin = $_SESSION["UserName"];



  // Functions.phpで作成した、現在時刻を取得する関数を格納
  $DateTime = getTime();


  // エラー時
  if (empty($PostTitle)) {
    $_SESSION["ErrorMessage"] = "Title can't be empty"; //エラーメッセをセッションに

    // Functions.phpで定義しているので、ここで指定した先にリダイレクトできるようになる
    Redirect_to("AddNewPost.php");
  // } elseif (strlen($Category) < 5) { //strlen — 文字列の長さを得る
  //   $_SESSION["ErrorMessage"] = "Post title should be greater than 5 characters";
  //   Redirect_to("AddNewPost.php");
  } elseif (strlen($PostText) > 999) { //strlen — 文字列の長さを得る
    $_SESSION["ErrorMessage"] = "Post desc should be less than 1000 characters";
    Redirect_to("AddNewPost.php");

    // 成功時
  } else {
    // 上記のValidationをスルーしたのでDBのpostsテーブルに値を入れていく
    $sql = "insert into posts(datetime, title, category, author, image, post)";

    // This is dummy (プレースホルダー。SQLインジェクション対策)
    $sql = $sql . "values(:dateTime, :postTitle, :categoryName, :adminName, :imageName, :postDescription)";

    // var_dump($sql);

    // connect.phpから取得した関数を格納 (sql文を実行する際に必要)
    $ConnectingDB = dbConnect();

    $stmt = $ConnectingDB->prepare($sql); // sql文は prepare()を通す必要がある

    //  bindValueは,対応する名前あるいは疑問符のプレースホルダに値をバインドする
    $stmt->bindValue(':dateTime', $DateTime); // 1.dummy, 2,実際の値
    $stmt->bindValue(':postTitle', $PostTitle); // 1.dummy, 2,実際の値
    $stmt->bindValue(':categoryName', $Category); // 1.dummy, 2,実際の値
    $stmt->bindValue(':adminName', $Admin); // 1.dummy, 2,実際の値
    $stmt->bindValue(':imageName', $Image); // 1.dummy, 2,実際の値
    $stmt->bindValue(':postDescription', $PostText); // 1.dummy, 2,実際の値

    // 実行するコードを格納
    $Execute = $stmt->execute();

    // 実際のimageをフォルダーに格納させる関数 (PHPはimgを tmp_name というフォルダに仮置きしてるので、それを自分で指定した先に持ってくる($Targetに) )
    move_uploaded_file($_FILES["Image"]["tmp_name"], $Target);

    // DBとやり取りするときはエラーが起きやすいのでIF文使用
    if ($Execute) {
      // 一番最後のIDを表示させる lastInsertID()関数をDBを通して実行
      $_SESSION["SuccessMessage"] = "あなたのPost with id : " . $ConnectingDB->lastInsertID() . "ADDED Successfully!!!!!!";

      // echo SuccessMessage();

      // Redirect_to("google.com");

    } else {
      $_SESSION["ErrorMessage"] = "Something went wrong!";
      Redirect_to("AddNewPost.php");
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
          <h1 class=""><i class="fas fa-edit"></i>Add New Post</h1>
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
        ?>

        <!-- enctype save the image to the folder wherever you want  -->
        <!-- enctype属性は、フォームの送信データのMIMEタイプを設定するための属性です。 属性値にMIMEタイプを示す文字列を指定することで、フォームのデータが送信される際に用いられるMIMEタイプを設定することができます。 -->
        <form action="AddNewPost.php" class="" method="post" enctype="multipart/form-data">
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
                <input class="form-control" type="text" name="PostTitle" id="title" placeholder="Type title here" value="">
              </div>

              <!-- ----------------------- -->
              <!-- Category (selection)-->
              <!-- ----------------------- -->
              <div class="form-group">
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
              <!-- <div class="form-group my-4" style="color: white;"> -->
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

                  <textarea class="form-control" name="PostDescription" id="Post" cols="30" rows="10"></textarea>

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
                    <i class="fas fa-check"></i>Publish
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