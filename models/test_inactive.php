<?php
    include_once 'db_connection.php'; 
    include_once 'renewal.php'; 
    require_once 'constants.php';
    
    for ($x = 0; $x <= 1; $x++) {
        echo "The number is: $x <br>";
        renew_listing();
    } 
    
    
    function renew_listing(){ 
        $access_token = '9a02a149a7ef40552db22df8e73f94';   
        $access_token_secret = 'd5a05c250d';
        $shop_id='7356972';

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
    }

