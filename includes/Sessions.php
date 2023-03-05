<?php
session_start();

function ErrorMessage() {
  if (isset( $_SESSION["ErrorMessage"] )) {
    $Output = "<div class=\"alert alert-danger\"> ";

    // it won't break HTML syntax ( concatenate )
$Output .= htmlentities($_SESSION["ErrorMessage"]);
$Output .= "</div>";

// this error  message with session needs to clear session, otherwise people all the time get error  
$_SESSION["ErrorMessage"] = null;

return $Output; // この関数から欲しいものをreturnする
  }
}


function SuccessMessage() {
  if (isset( $_SESSION["SuccessMessage"] )) {
    $Output = "<div class=\"alert alert-Success\"> ";

    // it won't break HTML syntax ( concatenate )
$Output .= htmlentities($_SESSION["SuccessMessage"]);
$Output .= "</div>";

// this error message needs to clear session, otherwise people all the time get error  
$_SESSION["SuccessMessage"] = null;

return $Output; // この関数から欲しいものをreturnする
  }
}



?>