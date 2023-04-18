<?php
// DB connection
include_once("./DB/connect.php");
dbConnect();

// Functions
require_once("Includes/Functions.php");

// Sessions
require_once("Includes/Sessions.php");

// 自分自身のページに飛ばすものを定義し、それをセッションに格納
// 現在のスクリプトが実行されているサーバの IP アドレスを返すもの
$_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];

// Functions.phpで設定した、ログインしてないと入れない様にする関数
Confirm_Login();
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
    $Admin = $_SESSION["UserName"];

  // Functions.phpで作成した、現在時刻を取得する関数を格納
  $DateTime = getTime();


  // エラー時 (どれか一つでも空ならエラー)
  if (empty($UserName) || empty($Password) || empty($ConfirmPassword)) {
    $_SESSION["ErrorMessage"] = "All fields must be filled out"; //エラーメッセをセッションに

    // Functions.phpで定義しているので、ここで指定した先にリダイレクトできるようになる
    Redirect_to("Admins.php");

    // 四文字以下じゃないとエラー
  } elseif (strlen($Password) < 4) { //strlen — 文字列の長さを得る
    $_SESSION["ErrorMessage"] = "password should be greater than 3 characters";
    Redirect_to("Admins.php");

    // 同じでないならエラー
  } elseif ($Password !== $ConfirmPassword) { //strlen — 文字列の長さを得る
    $_SESSION["ErrorMessage"] = "Password and Confirm Password should match";
    Redirect_to("Admins.php");

    // Functions.phpから関数を輸入 $UserNameはフォームで入力された値。(Trueが返ってきたらここでエラーを起こす)
  } elseif (CheckUserNameExistsOrNot( $UserName )) {
    $_SESSION["ErrorMessage"] = "UserName Exists. Try Another one";
    Redirect_to("Admins.php");
  }

   // 成功時  Query to insert new admin in DB when everything is fine
  else {
    // global $ConnectingDB;
    // 上記のValidationをスルーしたのでDBに値を入れていく
    $sql = "insert into admins(datetime, username, password, adname, addedby)";

    // This is dummy (プレースホルダー。SQLインジェクション対策)
    $sql = $sql . "values(:dateTime, :username, :password, :adname, :adminName)";

    // connect.phpから取得した関数を格納 (sql文を実行する際に必要)
    $ConnectingDB = dbConnect();

    $stmt = $ConnectingDB->prepare($sql); // sql文は prepare()を通す必要がある
    //  bindValueは,対応する名前あるいは疑問符のプレースホルダに値をバインドする
    $stmt->bindValue(':dateTime', $DateTime); // 1.dummy, 2,実際の値
    $stmt->bindValue(':username', $UserName); 
    $stmt->bindValue(':password', $Password); 
    $stmt->bindValue(':adname', $Name); 
    $stmt->bindValue(':adminName', $Admin); 

    // 実行するコードを格納
    $Execute = $stmt->execute();

    // var_dump($Execute); // Debugging


    // DBとやり取りするときはエラーが起きやすいのでIF文使用
    if ($Execute) {
      $_SESSION["SuccessMessage"] =  "New Admin with the name of $Name  ADDED Successfully!!!!!!";
      Redirect_to("Admins.php");

    } else {
      $_SESSION["ErrorMessage"] = "Something went wrong!";
      var_dump($Execute); // Debugging
      Redirect_to("Admins.php");
    }
  }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page</title>
  <link rel="stylesheet" href="./dist/app.css">

  <!-- fontawesome v6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <!--  -------->
  <!-- Navbar -->
  <!-- ------ -->

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="MyProfile.php" class="navbar-brand">KOJIMA.COM</a>

      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarcollapseCMS">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarcollapseCMS">

        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="Dashboard.php" class="nav-link">
              <i class="fas fa-user text-success"></i> My Profile</a>
          </li>
          <li class="nav-item">
            <a href="Posts.php" class="nav-link">Dashboard</a>
          </li>
          <li class="nav-item">
            <a href="Categories.php" class="nav-link">Posts</a>
          </li>
          <li class="nav-item">
            <a href="Admins.php" class="nav-link">Categories</a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">Manage Admins</a>
          </li>
          <li class="nav-item">
            <a href="Comments.php" class="nav-link">Comments</a>
          </li>
          <li class="nav-item">
            <a href="Blog.php?page=1" class="nav-link">Live Blog</a>
          </li>
        </ul>

        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="Logout.php" class="nav-link text-danger">
              <i class="fas fa-user-times"></i> Logout</a>
          </li>
        </ul>

      </div>
    </div>
  </nav>

  <!--  -------->
  <!-- header -->
  <!-- ------ -->
  <header class="bg-dark text-white py-3">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1 class=""><i class="fas fa-user"></i>Manage Admins</h1>
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
        <form action="Admins.php" class="" method="post">
          <div class="card mb-3">
            <div class="card-header">
              <h1 class="">Add New Admin</h1>
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


<!--  -->
        <h2>Existing Admins</h2>
        <table class="table table-striped table-hover">
          <thead class="thead-dark">
            <tr>
              <th>No. </th>
              <th>Date&Time</th>
              <th>UserName</th>
              <th>Admin Name</th>
              <th>Added by </th>
              <th>Action</th>
            </tr>
          </thead>
          <?php
          // 関数内からグローバル変数にアクセスする際に必要
          global $ConnectingDB;

          // statusが OFFのやつのみ を最新順で取得する
          $sql = "select * from admins  ORDER BY id desc ";
          $Execute = $ConnectingDB->query($sql);

          // HTMLに表示した時に番号を振り付ける　ex) 1 2 3 4 5 
          $SrNo = 0;

          // DB のカラムをループで取り出す
          while ($DataRows = $Execute->fetch()) :
            $AdminId = $DataRows["id"];
            $DateTime = $DataRows["datetime"];
            $AdminUsername = $DataRows["username"];
            $AdminName = $DataRows["adname"];
            $AddedBy = $DataRows["addedby"];
            $SrNo++; // increment

          ?>

            <!-- HTMLに出力（テーブル内） -->
            <tbody>
              <tr>
                <td><?php echo htmlentities($SrNo); ?></td>
                <td><?php echo htmlentities($DateTime); ?></td>
                <td><?php echo htmlentities($AdminUsername); ?></td>
                <td><?php echo htmlentities($AdminName); ?></td>
                <td><?php echo htmlentities($AddedBy); ?></td>

                <!-- Delete btn -->
                <td><a class="btn btn-danger" href="DeleteAdmin.php?id=<?php echo $AdminId; ?>">Delete</a></td>

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
  <footer class="bg-dark text-white">
    <div class="container">
      <div class="row">
        <!-- <div class="col"> -->
        <p class="lead text-center">
          &copy;KOJIMA ---- <span id="year"></span> All Right Reserved.
        </p>
      </div>
    </div>
    <!-- </div> -->
  </footer>


  <script src="./dist/app.js"></script>

</body>

</html>