<?php 
    session_start(); //session start

    // include database and object files 
    include_once 'db_connection.php'; 
    include_once 'payment_history.php'; 
    include_once 'myUser.php';

    //instantiate database and product object 
    $database = new db_connection(); 
    $db = $database->getConnection();
 
    // initialize object
    $payment_history = new payment_history($db);  
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
    
    $expiry_date=$_GET['end_date'];
    $payment_history->google_id=$userId;
    $payment_history->description= $_GET['dscr'];
    $payment_history->months= $_GET['months'];
    $payment_history->amount=$_GET['amount'];
    $payment_history->start_date=$_GET['start_date'];
    $payment_history->end_date=$expiry_date;
    
    //update session current user expiry date
    $user_data["user"]["expiry_date"]=$expiry_date;    
    $current_user=json_encode($user_data);
    $_SESSION['current_user']=$current_user;
    
    $stmt = $payment_history->add_payment();    