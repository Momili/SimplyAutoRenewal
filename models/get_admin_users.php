<?php 
    session_start(); //session start
    
    header("Access-Control-Allow-Origin: *"); 
    header("Content-Type: application/json; charset=UTF-8"); 

    // include database and object files 
    include_once 'constants.php';
    include_once 'db_connection.php'; 
    include_once 'myUser.php';     
    
    //instantiate database and product object 
    $database = new db_connection(); 
    $db = $database->getConnection();
 
    // initialize object
    $user = new myUser($db);

    $stmt = $user->read_all_users();
    $num = $stmt->rowCount();
    
    // check if more than 0 record found
    if($num>0){     
        $users_array=array();    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($users_array, $row);
        }         
    } 
    $post_data =array('users'=>$users_array);
    // json format output 
    echo (json_encode($post_data));    
