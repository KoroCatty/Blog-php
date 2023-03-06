<?php
function Redirect_to($New_Location) {
header("Location:". $New_Location); // go to this location
exit;
}


// 現在時刻を表示する関数を定義
function getTime() {
// タイムゾーンの設定
date_default_timezone_set("Asia/Tokyo");

$CurrentTime = time();

// 現在時刻を取得して格納  strftime()は非推奨になった
$DateTime = date("F j, Y, g:i a");    
// echo $DateTime;

return $DateTime;
}
