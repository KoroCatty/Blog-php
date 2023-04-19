<?php
// Header
include('./templates/Header.php');
?>

<?php
// Validation
// from name="Submit" ボタンから取得
if (isset($_POST["Submit"])) {
  $UserName = $_POST["Username"]; // フォームの値を格納
  $Name = $_POST["Name"]; // フォームの値を格納
  $Password = $_POST["Password"]; // フォームの値を格納
  $ConfirmPassword = $_POST["ConfirmPassword"]; // フォームの値を格納

  // Login.php で設定したセッションを使い、動的に名前を引き出して格納
  // $Admin = $_SESSION["UserName"];

  // Functions.phpで作成した、現在時刻を取得する関数を格納
  $DateTime = getTime();


  // エラー時 (どれか一つでも空ならエラー)
  if (empty($UserName) || empty($Password) || empty($ConfirmPassword)) {
    $_SESSION["ErrorMessage"] = "All fields must be filled out"; //エラーメッセをセッションに

    // Functions.phpで定義しているので、ここで指定した先にリダイレクトできるようになる
    Redirect_to("registerUser.php");

    // 四文字以下じゃないとエラー
  // } elseif (strlen($Password) < 4) { //strlen — 文字列の長さを得る
  //   $_SESSION["ErrorMessage"] = "password should be greater than 3 characters";
  //   Redirect_to("Admins.php");

    // 同じでないならエラー
  } elseif ($Password !== $ConfirmPassword) { //strlen — 文字列の長さを得る
    $_SESSION["ErrorMessage"] = "Password and Confirm Password should match";
    Redirect_to("registerUser.php");

    // Functions.phpから関数を輸入 $UserNameはフォームで入力された値。(Trueが返ってきたらここでエラーを起こす)
  } elseif (CheckUserNameExistsOrNot($UserName)) {
    $_SESSION["ErrorMessage"] = "UserName Exists. Try Another one";
    Redirect_to("registerUser.php");
  }

  // Validation 成功時  Query to insert new admin in DB when everything is fine
  else {

    // global $ConnectingDB;
    // 上記のValidationをスルーしたのでDBに値を入れていく
    $sql = "insert into admins(datetime, username, password, adname, addedby)";

    // This is dummy (プレースホルダー。SQLインジェクション対策)
    $sql = $sql . "values(:dateTime, :username, :password, :adname, :adminName)";
    // $sql = $sql . "values(aaa, aaa, aaa, aaa, aaa)";

    // connect.phpから取得した関数を格納 (sql文を実行する際に必要)
    $ConnectingDB = dbConnect();

    $stmt = $ConnectingDB->prepare($sql); // sql文は prepare()を通す必要がある
    //  bindValueは,対応する名前あるいは疑問符のプレースホルダに値をバインドする
    $stmt->bindValue(':dateTime', $DateTime); // 1.dummy, 2,実際の値
    $stmt->bindValue(':username', $UserName);
    $stmt->bindValue(':password', $Password);
    $stmt->bindValue(':adname', $Name);
    // $stmt->bindValue(':adminName', $Admin);
    $stmt->bindValue(':adminName', $UserName);

    // 実行するコードを格納
    $Execute = $stmt->execute();


    // DBとやり取りするときはエラーが起きやすいのでIF文使用
    if ($Execute) {
      $_SESSION["SuccessMessage"] =  "New Admin with the name of `$UserName`  ADDED Successfully!!!!!!";
      Redirect_to("login.php");

      
    } else {
      $_SESSION["ErrorMessage"] = "Something went wrong!";
    }
  }
}
?>

<!-- $UserId を持ってないとログアウトされる Functions.phpで定義 -->




<!--  -------->
<!-- header -->
<!-- ------ -->
<header class="bg-dark text-white py-3">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1 class=""><i class="fas fa-user"></i>User Register</h1>
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
      <form action="" class="" method="post">
        <div class="card mb-3">
          <div class="card-header">
            <h1 class="">Add New User</h1>
          </div>

          <div class="card-body bg-dark">

            <!-- Username -->
            <div class="form-group">
              <label for="username">
                <span class="FieldInfo">
                  Username:
                </span>
              </label>
              <input class="form-control" type="text" name="Username" id="username" placeholder="Type title here" value="">
            </div>

            <!-- Name -->
            <div class="form-group">
              <label for="Name">
                <span class="FieldInfo">
                  Name:
                </span>
              </label>
              <input class="form-control" type="text" name="Name" id="Name" value="">
              <small class="text-warning text-muted">Optional</small>
            </div>

            <!-- Password -->
            <div class="form-group">
              <label for="Password">
                <span class="FieldInfo">
                  Password:
                </span>
              </label>
              <input class="form-control" type="password" name="Password" id="Password" value="">
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
              <label for="ConfirmPassword">
                <span class="FieldInfo">
                  Confirm Password:
                </span>
              </label>
              <input class="form-control" type="password" name="ConfirmPassword" id="ConfirmPassword" value="">
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



<!-- $login->execute(array(
      $_POST['email'],
      sha1($_POST['password']) //暗号化されてデータベースに保存されているので、絶対に一致しない。なのでsha1()を使って一致させる。
    )); -->





<!--  -------->
<!-- Footer -->
<!-- ------ -->
<?php
include('./templates/Footer.php');
?>