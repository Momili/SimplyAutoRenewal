<?php 
    session_start(); //session start
    
    header("Access-Control-Allow-Origin: *"); 
    header("Content-Type: application/json; charset=UTF-8"); 

    // include database and object files 
    include_once 'db_connection.php'; 
    include_once 'renewal.php'; 

    //instantiate database and product object 
    $database = new db_connection(); 
    $db = $database->getConnection();
 
    // initialize object
    $renewal = new renewal($db);
    
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
    
    //$stmt = $renewal->readAll($userId, $shopId, 'S');
    $stmt = $renewal->read_scheduled($userId, $shopId);
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){     
        $renewals_array=array();    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($renewals_array, $row);
        }         
    } 
    $post_data =array('renewals'=>$renewals_array);
    // json format output 
    echo (json_encode($post_data));    
