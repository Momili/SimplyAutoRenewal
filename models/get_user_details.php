<?php

session_start();
if (isset($_SESSION['current_user']) && $_SESSION['current_user']) {
  $current_user = $_SESSION['current_user'];
  echo $current_user;
}

function getCurrentUser(){
    $userId="";
    if (isset($_SESSION['current_user']) && $_SESSION['current_user']) {
        $current_user = $_SESSION['current_user'];
        $user_data=json_decode($current_user, TRUE);
        $userId=$user_data["user"]["id"];
    }
    return $userId;
}