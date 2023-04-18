<?php
// DB connection
include_once("./DB/connect.php");
$ConnectingDB = dbConnect();

// Functions
require_once("Includes/Functions.php");

// Sessions
require_once("Includes/Sessions.php");
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

  <title>Blog Page</title>
</head>


<body>
  <!-- NAVBAR -->
  <div style="height:10px; background:#27aae1;"></div>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand"> KAZUYA.COM</a>
      <button class="navbar-toggler" data-toggle="collapse" data-target="#navbarcollapseCMS">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarcollapseCMS">
        <ul class="navbar-nav mr-auto">

          <li class="nav-item">
            <a href="Blog.php" class="nav-link">Home</a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">About Us</a>
          </li>
          <li class="nav-item">
            <a href="Blog.php" class="nav-link">Blog</a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">Contact Us</a>
          </li>
          <li class="nav-item">
            <a href="" class="nav-link">Features</a>
          </li>
        </ul>

        <ul class="navbar-nav ml-auto">

          <!-- spサイズではフォーム非表示 -->
          <form action="Blog.php" class="form-inline d-none d-sm-block">
            <div class="form-group">
              <input type="text" name="Search" placeholder="Search here" value="" class="form-control mr-2">
              <button class="btn btn-primary" name="SearchButton">Go</button>
            </div>
          </form>

        </ul>
      </div>
    </div>
  </nav>
  <div style="height:10px; background:#27aae1;"></div>
  <!-- NAVBAR END -->