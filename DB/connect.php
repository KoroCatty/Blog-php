<?php
// 接続確認方法
// http://localhost:8888/php/blog/database/connect.php

// DB接続を関数化
function dbConnect() {
  $user = "root";
  $pass = "root";

  try {
    // PDOクラスのインスタンスを作成
    $dbh = new PDO('mysql:host=localhost;dbname=blog', $user, $pass,
  [     
        // PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // prepare()をどこかで使う場合はこれが必要で、ないとSQL injection防げない
        // PDO::ATTR_EMULATE_PREPARES => false,
  ]
  );
    // echo "<h1>Connected DB </h1>";
  } catch (PDOException $er) { // errorという引数でエラー内容を受け取る
    echo $er->getMessage(); // $errorの中にある関数を取り出して表示
    exit();
  }
  return $dbh; // ここに欲しいものを返す。この関数で要るものは $dbh
}

// display_errorsをONに設定
// ini_set('display_errors', 1);