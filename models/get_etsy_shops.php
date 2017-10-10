<?php 
    session_start(); //session start
    
    header("Access-Control-Allow-Origin: *"); 
    header("Content-Type: application/json; charset=UTF-8"); 

    // include database and object files 
    include_once 'db_connection.php'; 
    include_once 'etsy_user.php'; 

    //instantiate database and product object 
    $database = new db_connection(); 
    $db = $database->getConnection();
 
    // initialize object
    $etsy_user = new etsy_user($db);
    
    // query renewals
    //$userId='1';//todo: get it from user login ?    
    if (isset($_SESSION['current_user']) && $_SESSION['current_user']) {
        $current_user = $_SESSION['current_user'];
        $user_data=json_decode($current_user, TRUE);
        $userId=$user_data["user"]["id"];
    }else{
        //todo: redirect to logout?
        exit;
    }
    
    $stmt = $etsy_user->get_user_details($userId);

    // check if more than 0 record found
    if( count($stmt)>0){     
        $shops_array=array();    
        //while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        //while ($row = mysql_fetch_array($stmt, MYSQL_ASSOC)) {        
        foreach ($stmt as &$row) {    
            array_push($shops_array, $row);
        }
    } 
    $post_data =array('shops'=>$shops_array);
    // json format output 
    echo (json_encode($post_data));  