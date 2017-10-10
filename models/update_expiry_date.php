<?php 
    session_start(); //session start

    // include database and object files 
    include_once 'db_connection.php'; 
    include_once 'myUser.php';

    //instantiate database and product object 
    $database = new db_connection(); 
    $db = $database->getConnection();
 
    // initialize object
    $newUser=new myUser($db);
    
    if (isset($_SESSION['current_user']) && $_SESSION['current_user']) {
        $current_user = $_SESSION['current_user'];
        $user_data=json_decode($current_user, TRUE);
        $userId=$user_data["user"]["id"];
        $shopId=$user_data["user"]["shop_id"];
    }else{
        exit;
    }   
    echo $userId;
    
    $newUser->google_id=$userId;
    $newUser->expiry_date=$_GET['end_date'];
    $stmt = $newUser->update_expiry_date();