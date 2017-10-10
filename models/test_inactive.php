<?php
    include_once 'db_connection.php'; 
    include_once 'renewal.php'; 
    require_once 'constants.php';
     
    $access_token = 'e36ce29441f02d94c9796ea9a621ac';   
    $access_token_secret = 'fa48e6690e';
    $shop_id='5486190';
        
    $oauth = new OAuth(ETSY_API_KEY, ETSY_SHARED_SECRET, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        
    $oauth->disableSSLChecks();
        
    $oauth->setToken($access_token, $access_token_secret);

    //$url = ETSY_API_URL.'/listings/'.$listing_id.'?renew=true';        
        
     
    
    $url="https://openapi.etsy.com/v2/shops/".$shop_id."/listings/expired";
    //echo $url;
    
    try {
        $data = $oauth->fetch($url, null, OAUTH_HTTP_METHOD_GET);
            
            var_dump($data);
            $json = $oauth->getLastResponse();
            var_dump((json_decode($json, true)) );

    } catch (OAuthException $e) {
            //TODO: LOG ERRORS
            //echo "error=".$e->getMessage();
            print($e->getMessage());
            print(print_r($oauth->getLastResponse(), true));
            print(print_r($oauth->getLastResponseInfo(), true));
            exit;
    }

