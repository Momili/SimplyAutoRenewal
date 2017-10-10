<?php

    require_once 'models/constants.php';
    
    session_start();    
    
    if (isset($_SESSION['current_user']) && $_SESSION['current_user']) {
        $current_user = $_SESSION['current_user'];
        $user_data = json_decode($current_user, TRUE);
        $google_id = $user_data["user"]["id"];
    }else{
        //todo: redirect to index.html
    }

    // get temporary credentials from the url
    $request_token = $_GET['oauth_token'];    

    // get the temporary credentials secret - this assumes you set the request secret  
    // in a cookie, but you may also set it in a database or elsewhere
    $request_token_secret = $_COOKIE['request_secret'];

    // get the verifier from the url
    $verifier = $_GET['oauth_verifier'];

    $oauth = new OAuth(ETSY_API_KEY, ETSY_SHARED_SECRET);

    $oauth->disableSSLChecks();

    // set the temporary credentials and secret
    $oauth->setToken($request_token, $request_token_secret);

    //GET ACCESS TOKENS
    try {
        // set the verifier and request Etsy's token credentials url
        $acc_token = $oauth->getAccessToken("https://openapi.etsy.com/v2/oauth/access_token", null, $verifier);
        //print_r( $acc_token);   
    } catch (OAuthException $e) {
        //TODO:REDIRECT BACK
        //error_log($e->getMessage());
        print $e->getMessage();
    }

    $access_token = $acc_token['oauth_token'];
    $access_token_secret = $acc_token['oauth_token_secret'];

    $oauth = new OAuth(ETSY_API_KEY, ETSY_SHARED_SECRET, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);

    $oauth->disableSSLChecks();

    $oauth->setToken($access_token, $access_token_secret);

    //GET SIGNED IN USER'S SHOP(S) DETAILS
    try {
        $data = $oauth->fetch("https://openapi.etsy.com/v2/users/__SELF__", null, OAUTH_HTTP_METHOD_GET);
        $json = $oauth->getLastResponse();
        $user_self=json_decode($json, true);
        //print_r(json_decode($json, true));
        $etsy_user_id= $user_self['results'][0]['user_id'];
        $etsy_login_name= $user_self['results'][0]['login_name'];

    } catch (OAuthException $e) {
        //todo: redirect back
        print $e->getMessage();
        error_log($e->getMessage());
        error_log(print_r($oauth->getLastResponse(), true));
        error_log(print_r($oauth->getLastResponseInfo(), true));
        exit;
    }
//"user_id":7002018, "shop_id":5486190,"shop_name":"yutal"
//"user_id":21885378,"shop_id":6923405,"shop_name":"DotsByYutal"
//"user_id":90824007,"login_name":"HurleyMistrall",shop_id":13070652,"shop_name":"YasminStudioDesign"
    
$test_user_id= $etsy_user_id; //Yutal temporary for TESTING!!!

//echo $etsy_user_id;

$url='https://openapi.etsy.com/v2/users/'.$test_user_id.'/shops/?'
        . 'fields=user_id,shop_id,shop_name,title,login_name,url,image_url_760x100,icon_url_fullxfull&'
        . "api_key=".ETSY_API_KEY;

        echo $url;
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response_body = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (intval($status) != 200) {
            //todo: redirect back
            throw new \Exception("HTTP $status\n$response_body");
        }
        
        //SAVE TO DATABASE    
        // get database connection 
        include_once 'models/db_connection.php'; 
        $database = new db_connection(); 
        $db = $database->getConnection();
        
        include_once 'models/etsy_user.php';
        $new_user=new etsy_user($db);
        
        $user_details=json_decode($response_body, true);
        $is_default=1;
        foreach ($user_details['results'] as $result){
            //check if etsy shop already exists
            $count = $new_user->get_count($google_id, $result['user_id'], $result['shop_id']);
            if($count==0){
                //reset default for this google user
                $new_user->reset_default($google_id);
                //create new user/shop
                $new_user->google_id=$google_id;
                $new_user->user_id=$result['user_id'];
                $new_user->login_name=$result['login_name'];
                $new_user->title=$result['title'];
                $new_user->shop_id=$result['shop_id'];
                $new_user->shop_name=$result['shop_name'];
                $new_user->shop_url=$result['url'];
                $new_user->access_token=$access_token;
                $new_user->access_token_secret=$access_token_secret;
                $new_user->banner_url=$result['image_url_760x100'];
                $new_user->icon_url=$result['icon_url_fullxfull'];
                $new_user->is_default=$is_default;
                $new_user->status='A';                
                $new_user->create_new();
            }
        }
        
        echo '<script type="text/javascript">
                window.location.href = "/'.MY_DOMAIN.'main.html";
              </script>';
        
         
        