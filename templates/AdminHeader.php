<?php
// DB connection
include_once("./DB/connect.php");
$ConnectingDB = dbConnect();

// Functions
require_once("includes/Functions.php");

// Sessions
require_once("includes/Sessions.php");

// Functions.phpで設定した、ログインしてないと入れない様にする関数
Confirm_Login();
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
    '/php/blog/Dashboard.php' => 'DashBoard｜' . $defaultTitle,
    '/php/blog/Posts.php' => 'Blog Posts｜' . $defaultTitle,
    '/php/blog/Categories.php' => 'Categorories｜' . $defaultTitle,
    '/php/blog/Admins.php' => 'Mange Admins｜' . $defaultTitle,
    '/php/blog/Comments.php' => 'Comments｜' . $defaultTitle,
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
      <div class="container">
        <!-- Logo Link -->
        <a href="Dashboard.php" class="navbar-brand">KOJIMA PET</a>

        <!-- Hamburger btn -->
        <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarcollapseCMS">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarcollapseCMS">
          <ul class="navbar-nav">
            <li class="nav-item">
              <a href="Dashboard.php" class="nav-link">Dashboard</a>
            </li>
            <li class="nav-item">
              <a href="Posts.php" class="nav-link">All Posts</a>
            </li>
            <li class="nav-item">
              <a href="Categories.php" class="nav-link">
                <i class="fas fa-folder-plus"></i>
                Categories
              </a>
            </li>
            <li class="nav-item">
              <a href="Admins.php" class="nav-link">
                <i class="fas fa-user-plus"></i>
                Manage Admins
              </a>
            </li>
            <li class="nav-item">
              <a href="Comments.php" class="nav-link">
                <i class="fas fa-check"></i>
                Comments
              </a>
            </li>
            <li class="nav-item">
              <a href="Blog.php?page=1" target="_blank" class="nav-link">
                <i class="fa-solid fa-file-export"></i>
                Live Blog
              </a>
            </li>
          </ul>

          <ul class="navbar-nav" style="gap: 20px;">
            <li class="nav-item">
              <a href="Logout.php" class="nav-link text-danger">
                <i class="fas fa-user-times"></i> Logout</a>
            </li>

            <?php
            // Login.phpで設定したセッションを使い格納(ログインの有無を判断)
            if (isset($_SESSION["UserName"])) {
              $Admin = $_SESSION["UserName"];
            }
            ?>

            <!-- ログイン時のみ表示  (ユーザー名)-->
            <?php
            if (isset($Admin)) {
              echo '
              <li class="text-white fs-4">' .
                '<i class="fa-regular fa-user text-white"></i>'
                . htmlspecialchars($Admin, ENT_QUOTES, 'UTF-8') . '</li>
              ';
            } ?>

          </ul>
        </div>
      </div>
    </nav>
  </header>