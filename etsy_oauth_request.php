<?php

    require_once 'models/constants.php';
    
    $oauth = new OAuth(ETSY_API_KEY, ETSY_SHARED_SECRET);
    
    // local
    $redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/etsy_get_token.php';    
    //
    /* BlueHost
    $redirect_uri = 'http://www.simplyautorenewal.com/etsy_get_token.php';
    */
    
    
    $oauth->disableSSLChecks(); 
    
    // make an API request for your temporary credentials
    $req_token = $oauth->getRequestToken("https://openapi.etsy.com/v2/oauth/request_token?scope=listings_w", $redirect_uri);

    //save oauth_token_secret in a cookie for later
    setcookie("request_secret", $req_token['oauth_token_secret']);       
    
    header('Location:'.$req_token['login_url']);