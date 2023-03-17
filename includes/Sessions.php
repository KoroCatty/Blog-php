<?php
session_start();
// =========================================================
// エラー時
// =========================================================
function ErrorMessage() {
  // ErrorMessageはcategories.phpから輸入　
  if (isset( $_SESSION["ErrorMessage"] )) {

    // エラーメッセを格納 (use escape)
    $Output = "<div class=\"alert alert-danger\"> ";

    // 適用可能な文字を全て HTML エンティティに変換 
$Output .= htmlentities($_SESSION["ErrorMessage"]);
$Output .= "</div>";
// same as $Output = $Output . "</div>" ( concatenate )

// this error message with session needs to clear session, otherwise all the time error  
$_SESSION["ErrorMessage"] = null;

return $Output; // この関数から欲しいものをreturnする
  }
}

// =========================================================
// 成功時
// =========================================================
function SuccessMessage() {
  if (isset( $_SESSION["SuccessMessage"])) {
    $Output = "<div class=\"alert alert-success\"> ";

    // it won't break HTML syntax
$Output .= htmlentities($_SESSION["SuccessMessage"]);
$Output .= "</div>";
// same as $Output = $Output . "</div>" ( concatenate )

// this error message with session needs to clear session, otherwise all the time error  
$_SESSION["SuccessMessage"] = null;

return $Output; // この関数から欲しいものをreturnする
  }
}
