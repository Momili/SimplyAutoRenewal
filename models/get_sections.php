<?php
    
    session_start(); //session start   
    
    include_once 'constants.php';

    if (isset($_SESSION['current_user']) && $_SESSION['current_user']) {
        $current_user = $_SESSION['current_user'];
        $user_data=json_decode($current_user, TRUE);
        $user_id=$user_data["user"]["id"];
        $shop_id=$user_data["user"]["shop_id"];
    }else{
        exit;
    }    

    //define("ETSY_API_KEY", "wh5yhq82mbfntus5nkby8yti");
    
    function httpGet($shop_id){
        $url="https://openapi.etsy.com/v2/shops/".$shop_id."/sections?api_key=".ETSY_API_KEY;

        //echo $url;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response_body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (intval($status) != 200) {
            throw new \Exception("HTTP $status\n$response_body");
        }
        else{
            return $response_body;
        }
    }
    
    //$shop_id=5251897;
    $section_array=array();
    $response_body=httpGet($shop_id);    
    $data = json_decode($response_body, TRUE);
    /*
    array_push($section_array, array(
                "shop_section_id"=>0,
                "title"=>"All Items")
        );
     * 
     */
    for($i=0; $i<count($data['results']); $i++) {
        if($data['results'][$i]["active_listing_count"]>0){            
            array_push($section_array, array(
                "shop_section_id"=>$data['results'][$i]["shop_section_id"],
                "title"=>$data['results'][$i]["title"]." (".$data['results'][$i]["active_listing_count"].")"
            ));
        }
    }  
    
    
    
    $data = json_decode($response_body, TRUE);
    $listing_count=count($data['results']);
    
    $post_data =array('sections'=>$section_array);
    // json format output 
    echo (json_encode($post_data));    