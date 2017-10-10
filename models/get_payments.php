<?php 
    session_start(); //session start
    
    header("Access-Control-Allow-Origin: *"); 
    header("Content-Type: application/json; charset=UTF-8"); 

    // include database and object files 
    include_once 'db_connection.php'; 
    include_once 'payment_history.php'; 

    //instantiate database and product object 
    $database = new db_connection(); 
    $db = $database->getConnection();
 
    // initialize object
    $payment_history = new payment_history($db);
    
    // query renewals
    //$userId='1';//todo: get it from user login ?    
    if (isset($_SESSION['current_user']) && $_SESSION['current_user']) {
        $current_user = $_SESSION['current_user'];
        $user_data=json_decode($current_user, TRUE);
        $userId=$user_data["user"]["id"];
        $shopId=$user_data["user"]["shop_id"];
    }else{
        exit;
    }   
    
    $stmt = $payment_history->get_payment_history($userId);
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){     
        $payments_array=array();    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($payments_array, $row);
        }         
    } 
    $post_data =array('payments'=>$payments_array);
    // json format output 
    echo (json_encode($post_data));    
