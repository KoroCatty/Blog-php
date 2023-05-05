<?php require("./templates/Header.php"); ?>

<?php
// ログイン後このページに来ようとしたらリダイレクトする (Loginページに来れるのはおかしいので)
if (isset($_SESSION["UserId"])) {
  header( "location:Dashboard.php");
}
?>

<?php
// submitボタンが押されたら発火し以下の全てのコードが実行
if (isset($_POST["Submit"])) {
  // save the input value
  $UserName = $_POST["Username"]; 
  $Password = $_POST["Password"];

  // 一つでも空なら実行 (error)
  if (empty($UserName) || empty($Password)) {
    $_SESSION["ErrorMessage"] = "ALL fields must be filled out";
    // Redirect_to("Login.php");

    // 成功なのでDBとコネクト(Success)
  } else {
    // check username & password form DB + save 
    // (Functions.php) 
    $Found_Account = Login_Attempt($UserName, $Password);

    // もし null ではない、何かの値が入っていればTRUEとみなされて実行し、IF文の中では、sessionにadminsテーブルから取得してきた各カラムを入れる
    if ($Found_Account) {
      // adminsテーブルのカラムを、左側のSESSIONの自由に設定した名前に格納
      $_SESSION["UserId"] = $Found_Account["id"];
      $_SESSION["UserName"] = $Found_Account["username"];
      $_SESSION["AdminName"] = $Found_Account["adname"];

      // 各ページで設定したセッションをここで使用してるので、ログインしたらsessionが残っているページに飛ぶ
      if (isset($_SESSION["TrackingURL"])) {
        Redirect_to($_SESSION["TrackingURL"]);

        // sessionがなかったら、ここに飛ばす
      } else {
        header( "location:Dashboard.php");
      }

      // 1以外の数字なら失敗なのでこれが実行
    } else {
      $_SESSION["ErrorMessage"] = "Incorrect Username / Password";
    }
  }
}
?>

<!-- Top line -->
<div style="height:10px; background:rgb(255,255,255);"></div>
<!-- NAVBAR END -->

<!-- HEADER -->
<header class="bg-dark text-white py-3">
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h1>Login</h1>
      </div>
    </div>
  </div>
</header>
<!-- HEADER END -->
<br>

<!-- Main Area -->
<section class="loginBox container py-2 mb-4">
  <div class="row">
    <!-- offsetで真ん中に寄せる -->
    <div class="offset-sm-3 col-sm-6 col-sm-6">

      <!-- セッションでエラー表示 -->
      <?php
      echo ErrorMessage();
      echo SuccessMessage();
      ?>

      <div class="card bg-secondary text-light">
        <div class="card-header">
          <h4 class="">Well come back !</h4>
        </div>
        <div class="card-body bg-dark">

          <!-- Username -->
          <form action="Login.php" method="post" class="">
            <div class="form-group">
              <label for="username"><span class="FieldInfo">
                  Username:
                </span></label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text text-white bg-info">
                    <i class="fas fa-user"></i>
                  </span>
                </div>
                <input type="text" name="Username" id="username" class="form-control" value="<?php if(isset($UserName)){
                  echo htmlentities($UserName);
                } ?>">
              </div>
            </div>

            <!-- Password -->
            <div class="form-group">
              <label for="password"><span class="FieldInfo">
                  Password:
                </span></label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text text-white bg-info">
                    <i class="fas fa-lock"></i>
                  </span>
                </div>
                <input type="password" name="Password" id="password" value="" class="form-control">
              </div>
            </div>

            <!-- Submit button -->
            <input type="submit" name="Submit" value="Login" class="btn btn-info btn-block">
          </form>
        </div>
      </div>

      <!-- Register page Link -->
      <div class="">
        <a href="RegisterUser.php" class="text-success"> Do you need register?</a>
      </div>
    </div>
  </div>
</section>
<!--  -------->
<!-- Footer -->
<!-- ------ -->
<?php
include('./templates/Footer.php');
?>