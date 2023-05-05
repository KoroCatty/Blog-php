<?php
// DB connection
include_once("./DB/connect.php");
$ConnectingDB = dbConnect();

// Functions
require_once("includes/Functions.php");

// Sessions
require_once("includes/Sessions.php");

// 自分自身のページに飛ばすものを定義し、それをセッションに格納
// 現在のスクリプトが実行されているサーバの IP アドレスを返すもの
// $_SESSION["TrackingURL"] = $_SERVER["PHP_SELF"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <?php
  // タイトルを動的に出力 ===================================

  // get the current URL ex) /php/blog/Dashboard.php
  $url = $_SERVER['REQUEST_URI'];

  // Default value
  $defaultTitle = 'KOJIMA.Pet';

  $titleName = [
    '/php/blog/index.php' => 'Home｜' . $defaultTitle,
    '/php/blog/About.php' => 'About Us｜' . $defaultTitle,
    '/php/blog/Blog.php' => 'Blog｜' . $defaultTitle,
    '/php/blog/Contact.php' => 'Contact｜' . $defaultTitle,
    '/php/blog/Login.php' => 'Login｜' . $defaultTitle,
    '/php/blog/RegisterUser.php' => 'Register｜' . $defaultTitle,
  ];

  // 「null合体演算子 (null coalescing operator)」
  // $titleName[$url]が存在しない場合、$defaultTitleが代入されます。これにより、配列にURLに対応するタイトルがない場合にも、デフォルトのタイトルを使用することができます。
  $titleTxt = $titleName[$url] ?? $defaultTitle;

  echo '<title>' . $titleTxt . '</title>';
  ?>

  <!-- Custom CSS -->
  <link rel="stylesheet" href="./dist/app.css">

  <!-- fontawesome v6 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
  <!--  ----------------->
  <!-- Loading Screen  -->
  <!--  ----------------->
  <div id="js_loading" class="bl_loading">
    <div class="bl_loadingCircle"></div>
    <div class="bl_loadingText">loading...</div>
  </div>

  <!--  -------->
  <!-- Navbar -->
  <!-- ------ -->
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
      <div class="headerContainer container">

      <!-- Logo -->
      <a href="index.php" class="navbar-brand">
          <img src="./src/img/logo.png" alt="logo" class="headerLogo">
          KOJIMA PET
        </a>

        <!-- Hamburger Btn -->
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarcollapseCMS">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Navbar Links -->
        <div class="collapse navbar-collapse" id="navbarcollapseCMS">
          <ul class="navbar-nav" style="gap:10px;">
            <li class="nav-item">
              <a href="index.php" class="nav-link">Home</a>
            </li>
            <li class="nav-item">
              <a href="About.php" class="nav-link">About Us</a>
            </li>
            <li class="nav-item">
              <a href="Blog.php" class="nav-link">Blog</a>
            </li>
            <li class="nav-item">
              <a href="Contact.php" class="nav-link">Contact Us</a>
            </li>

            <?php
            // Login.phpで設定したセッションを使い格納(ログインの有無を判断)
            if (isset($_SESSION["UserName"])) {
              $Admin = $_SESSION["UserName"];
            }
            ?>

            <!-- ログイン時のみ表示 -->
            <?php
            if (isset($Admin)) {
              echo '<li class="nav-item">
                <a href="Dashboard.php" class="nav-link text-danger" target="blank">
                  <i class="fa-solid fa-file-export"></i>
                  Go to Admin
                </a>
              </li>
              <li class="nav-item">
              <a href="Logout.php" class="nav-link text-danger">
                  <i class="fas fa-user-times"></i> Logout</a>
              </li>
              ';
            } ?>

            <!-- spサイズではフォーム非表示 -->
            <li class="">
              <form action="Blog.php" class="form-inline d-none d-sm-block ml-5">
                <div class="form-group d-flex">
                  <input type="text" name="Search" placeholder="Search here" value="" class="form-control mr-2">
                  <button class="btn btn-primary" name="SearchButton">Go</button>
                </div>
              </form>
            </li>

            <!-- ログインボタン　(ログイン時は表示しない) -->
            <?php
            // when logged in
            if (empty($Admin)) {
              echo '<li class="headerLoginBtn">
              <a href="./Login.php">LOGIN</a>
            </li>';
            };
            ?>

            <!-- ログイン時のみ表示  (ユーザー名)-->
            <?php
            if (isset($Admin)) {
              echo '
              <li class="text-white fs-4">' .
                '<i class="fa-regular fa-user text-white"></i>'
                . htmlentities($Admin) . '</li>
              ';
            } ?>
          </ul>

        </div>
      </div>
    </nav>
  </header>