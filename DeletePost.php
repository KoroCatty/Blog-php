<?php
// Header
include('./templates/AdminHeader.php');
?>
<?php
// URL bar (id=  1 )などを取得
$SearchQueryParameter = $_GET["id"];
?>

<?php
// =========================================
// Fetching Existing Content to delete easily
// =========================================
global $ConnectingDB;

// Get URL parameter from URL Bar
// $SearchQueryParameter = $_GET["id"];  // id=8 などを取得

// SQL文でデータをDBから取得
$sql = "select * from posts where id = '$SearchQueryParameter' ";
$stmt = $ConnectingDB->query($sql);

// postsテーブルから各カラムを取得　そしてこのデータをHTMLに注入し、editしやすくする
while ($DataRows = $stmt->fetch()) {
  $TitleToBeDeleted = $DataRows["title"];
  $CategoryToBeDeleted = $DataRows["category"];
  $ImageToBeDeleted = $DataRows["image"];
  $PostToBeDeleted = $DataRows["post"];
}



// echo $ImageToBeUpdated;
?>

<?php
if (isset($_POST["Submit"])) {

  // Query to Delete Post in DB when everything is fine 
  global $ConnectingDB;

  // idと一致するものだけ消す
  $sql = "DELETE FROM posts WHERE id = $SearchQueryParameter";

  $Execute = $ConnectingDB->query($sql);

  // var_dump($Execute); // Debugging

  // DBとやり取りするときはエラーが起きやすいのでIF文使用
  if ($Execute) {

    // Uploadsフォルダにある画像ファイルを格納
    $Target_Path_To_DELETE_Image = "Uploads/$ImageToBeDeleted";

    // この関数で画像を消す
    unlink($Target_Path_To_DELETE_Image);

    // 一番最後のIDを表示させる lastInsertID()関数をDBを通して実行
    $_SESSION["SuccessMessage"] = "あなたのPost with id : " . $ConnectingDB->lastInsertID() . "DELETED Successfully!!!!!!";

    // echo SuccessMessage();

    Redirect_to("Posts.php");
  } else {
    $_SESSION["ErrorMessage"] = "Something went wrong!";
    Redirect_to("Posts.php");
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
          <h1 class=""><i class="fas fa-edit"></i>Delete Post</h1>
        </div>
      </div>
    </div>
  </div>

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
        <form action="DeletePost.php?id=<?php echo htmlentities($SearchQueryParameter); ?>" class="" method="post" enctype="multipart/form-data">
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
                <input disabled class="form-control" type="text" name="PostTitle" id="title" placeholder="Type title here" value="<?php echo htmlentities($TitleToBeDeleted); ?>">
              </div>

              <!-- ----------------------- -->
              <!-- Category (selection)-->
              <!-- ----------------------- -->
              <div class="form-group">
                <span class="FieldInfo">Current Category: </span>

                <!-- DBにある現在のカテゴリーを表示 -->
                <span class="existCategory"><?php echo htmlentities($CategoryToBeDeleted); ?></span>

                <br />

              </div>


              <!-- ----------------------- -->
              <!-- Image File  -->
              <!-- ----------------------- -->
              <div class="form-group my-4" style="color: white;">
                <span class="FieldInfo">Current Image: </span>

                <!-- DBにある現在のimageを表示 -->
                <img src="./Uploads/<?php echo htmlentities($ImageToBeDeleted); ?>" alt="" class="editImg mb-1">

                <div class="custom-file">
                  <!-- <input type="File" name="Image" id="imageSelect" value="" class="custom-file-input"> -->

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
                  <textarea disabled class="form-control" name="PostDescription" id="Post" cols="30" rows="10"><?php echo htmlentities($PostToBeDeleted); ?></textarea>

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

                  <button class="btn btn-danger btn-block" type="submit" name="Submit">
                    <i class="fas fa-trash"></i>Delete
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