<?php

    session_start(); //session start
    
    include_once 'constants.php';

    if (isset($_SESSION['current_user']) && $_SESSION['current_user']) {
        $current_user = $_SESSION['current_user'];
        $user_data=json_decode($current_user, TRUE);
        $google_id=$user_data["user"]["id"];//google_id
        
        //get from param
        $user_id= $_GET['user_id'];
        $shop_id= $_GET['shop_id'];
        
        $rows = get_shop_details($google_id, $user_id, $shop_id);
        //echo $rows[0]['shop_name'];
        //echo $rows[0]['icon_url'];
        
        $user_data["user"]["etsy_user_id"]=$user_id;
        $user_data["user"]["shop_id"]=$shop_id;
        $user_data["user"]["shop_name"]=$rows[0]['title'];
        $user_data["user"]["shop_icon_url"]=$rows[0]['icon_url'];
                
        $_SESSION['current_user']=json_encode($user_data);
        
        set_default_shop($google_id, $user_id, $shop_id);
        
        echo json_encode($user_data);
        
    }else{
        //todo:redirect index/logout
        exit;
    }
/*
echo $google_id.'\n';
echo $user_id.'\n';
echo $shop_id.'\n';
 * 
 */
function set_default_shop($google_id, $user_id, $shop_id){
    
    include_once 'db_connection.php'; 
    $database = new db_connection(); 
    $db = $database->getConnection();
        
    include_once 'etsy_user.php';
    $etsy_user=new etsy_user($db);
    
    $etsy_user->reset_default($google_id);
    
    $etsy_user->set_default($google_id, $user_id, $shop_id);    
}

function get_shop_details($google_id, $user_id, $shop_id){
    
    include_once 'db_connection.php'; 
    $database = new db_connection(); 
    $db = $database->getConnection();
        
    include_once 'etsy_user.php';
    $etsy_user=new etsy_user($db);
    
    return $etsy_user->get_shop_details($google_id, $user_id, $shop_id);    

}