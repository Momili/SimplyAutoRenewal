<?php 
    session_start(); //session start

    // include database and object files 
    include_once 'db_connection.php'; 
    include_once 'renewal.php'; 

    //instantiate database and product object 
    $database = new db_connection(); 
    $db = $database->getConnection();
 
    // initialize object
    $renewal = new renewal($db);
    
    if (isset($_SESSION['current_user']) && $_SESSION['current_user']) {
        $current_user = $_SESSION['current_user'];
        $user_data=json_decode($current_user, TRUE);
        $userId=$user_data["user"]["id"];
        $shopId=$user_data["user"]["shop_id"];
    }else{
        exit;
    }   
    echo $userId;
    echo $shopId;
            
    
    $stmt = $renewal->delete_all_scheduled($userId, $shopId);