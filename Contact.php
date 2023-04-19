<?php
// DB connection
include_once("./DB/connect.php");
dbConnect();

// Functions
require_once("Includes/Functions.php");

// Sessions
require_once("Includes/Sessions.php");

//Header
include("./templates/Header.php");


?>


    <h2>ブログフォーム</h2>
    <form action="blog_create.php" method="POST">
        <p>ブログタイトル：</p>
        <input type="text" name="title">
        <p>ブログ本文：</p>
        <textarea name="content" id="content" cols="30" rows="10"></textarea>
        <br>

        <p>カテゴリ：</p>
        <select name="category">
            <option value="1">日常</option>
            <option value="2">プログラミング</option>
        </select>
        <br>

        <input type="radio" name="publish_status" value="1" checked>公開
        <input type="radio" name="publish_status" value="2">非公開
        <br>
        
        <input type="submit" value="送信">
    </form>

<?php
// Footer
include("./templates/Footer.php")
?>