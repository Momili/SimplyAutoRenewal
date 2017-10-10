<?php 
    session_start(); //session start
    
    header("Access-Control-Allow-Origin: *"); 
    header("Content-Type: application/json; charset=UTF-8"); 

    // include database and object files 
    include_once 'db_connection.php'; 
    include_once 'myUser.php'; 

    //instantiate database and product object 
    $database = new db_connection(); 
    $db = $database->getConnection();
 
    // initialize object
    $myUser = new myUser($db);
    
    // query renewals
    if (isset($_SESSION['current_user']) && $_SESSION['current_user']) {
        $current_user = $_SESSION['current_user'];
        $user_data=json_decode($current_user, TRUE);
        $userId=$user_data["user"]["id"];
    }else{
        echo '<script type="text/javascript">
                window.location.href = "/'.MY_DOMAIN.'index.html"
              </script>'; 
    }   
    
    $stmt = $myUser->getUserDetails($userId);
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num>0){     
        $user_array=array();    
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            array_push($user_array, $row);
        }         
    } 
    $post_data =array('user'=>$user_array);
    // json format output 
    echo (json_encode($post_data));