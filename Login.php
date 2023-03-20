<?php
// DB connection
include_once("./DB/connect.php");
dbConnect();

// Functions
require_once("Includes/Functions.php");

// Sessions
require_once("Includes/Sessions.php");
?>
<?php
// inputタグのsubmitボタンが押されたら発火
if (isset($_POST["Submit"])) {
  $UserName = $_POST["Username"]; // inputのnameから取得
  $Password = $_POST["Password"];

  // 一つでも空なら実行
  if (empty($UserName) || empty($Password)) {
    $_SESSION["ErrorMessage"] = "ALL fields must be filled out";
    Redirect_to("Login.php");

    // 成功なのでDBとコネクト
  } else {
    // check username & password form DB and then 格納 (関数はFunctions.phpで定義)
    $Found_Account = Login_Attempt($UserName, $Password);

    // もし null ではない、何かの値が入っていればTRUEとみなされて実行し、IF文の中では、sessionにadminsテーブルから取得してきた各カラムを入れる
    if ($Found_Account) {

      // adminsテーブルのカラムを、左側のSESSIONの自由に設定した名前に格納
      $_SESSION["UserId"] = $Found_Account["id"];
      $_SESSION["UserName"] = $Found_Account["username"];
      $_SESSION["Eggman"] = $Found_Account["adname"];

      // すぐ上で定義した、Eggmanの、adname を表示する
      $_SESSION["SuccessMessage"] = "wellcome" . $_SESSION["Eggman"];
      Redirect_to("Login.php");

      // 1以外の数字なら失敗なのでこれが実行
    } else {
      $_SESSION["ErrorMessage"] = "Incorrect Username / Password";
      Redirect_to("Login.php");
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
  <link rel="stylesheet" href="./dist/app.css">
  <title>Login Page</title>
</head>

<body>
  <!-- NAVBAR -->
  <div style="height:10px; background:#27aae1;"></div>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand"> JAZEBAKRAM.COM</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarcollapseCMS">

      </div>
    </div>
  </nav>
  <div style="height:10px; background:#27aae1;"></div>
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
                  <input type="text" name="Username" id="username" value="" class="form-control">
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
      </div>
    </div>
  </section>














  <!-- FOOTER -->
  <footer class="bg-dark text-white">
    <div class="container">
      <div class="row">
        <div class="col">
          <p class="lead text-center">Theme By | Jazeb Akram | <span id="year"></span> &copy; ----All right Reserved.</p>
          <p class="text-center small"><a style="color: white; text-decoration: none; cursor: pointer;" href="http://jazebakram.com/coupons/" target="_blank"> This site is only used for Study purpose jazebakram.com have all the rights. no one is allow to distribute copies other then <br>&trade; jazebakram.com &trade; Udemy ; &trade; Skillshare ; &trade; StackSkills</a></p>
        </div>
      </div>
    </div>
  </footer>
  <div style="height:10px; background:#27aae1;"></div>
  <!-- FOOTER END-->

  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
  <script>
    $('#year').text(new Date().getFullYear());
  </script>
</body>

</html>