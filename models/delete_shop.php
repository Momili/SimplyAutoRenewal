<?php 

// include database and object files 
    include_once 'db_connection.php'; 
    include_once 'etsy_user.php'; 

    //instantiate database and product object 
    $database = new db_connection(); 
    $db = $database->getConnection();
 
    // initialize object
    $etsy_user = new etsy_user($db);

    $user_id= $_GET['user_id'];
    $shop_id= $_GET['shop_id'];
    $etsy_user->remove_shop($user_id, $shop_id); //set status=Inactive

//echo "OK";