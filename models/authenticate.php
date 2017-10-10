<?php
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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