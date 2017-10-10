<?php

session_start(); //session start

require_once ('libraries/Google/autoload.php');
//includes
include_once 'models/constants.php';  
include_once 'models/myUser.php';
include_once 'models/db_connection.php'; 

//Insert your cient ID and secret  
//You can get it from : https://console.developers.google.com/
//$client_id = '344834169987-6dmftkc7m6jb6ql13qnjidpjjch0i9gc.apps.googleusercontent.com'; 
//$client_secret = 'ikea3z8h2_OSERpE4G9OBT3d';
//$redirect_uri = 'http://blendin.ca/'.MY_DOMAIN.'login.php';
$redirect_uri = 'http://'.$_SERVER['HTTP_HOST'].dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login.php/';
//$redirect_uri = 'http://www.simplyautorenewal.com/login.php/';

$database = new db_connection(); 
$db = $database->getConnection();
$newUser=new myUser($db);

//incase of logout request, just unset the session var
if (isset($_GET['logout']) or isset($_GET['error'])) {
  unset($_SESSION['access_token']);
  unset($_SESSION['current_user']);
  $uri='http://'.$_SERVER['HTTP_HOST'].dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/index.html';
  header('Location: ' . filter_var($uri, FILTER_SANITIZE_URL));  

  //echo '<script type="text/javascript">
  //          window.location.href = "/'.MY_DOMAIN.'index.html"
  //      </script>';    
}

/************************************************
  Make an API request on behalf of a user. In
  this case we need to have a valid OAuth 2.0
  token for the user, so we need to send them
  through a login flow. To do this we need some
  information from our API console project.
 ************************************************/
$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri($redirect_uri);
//next two line added to obtain refresh_token
//$client->setAccessType('offline');
$client->setApprovalPrompt('force');
$client->addScope("email");
$client->addScope("profile");

/************************************************
  When we create the service here, we pass the
  client to it. The client then queries the service
  for the required scopes, and uses that when
  generating the authentication URL later.
 ************************************************/
$service = new Google_Service_Oauth2($client);
/************************************************
  If we have a code back from the OAuth 2.0 flow,
  we need to exchange that with the authenticate()
  function. We store the resultant access token
  bundle in the session, and redirect to ourself.
*/ 

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
  exit;
}
/************************************************
  If we have an access token, we can make
  requests, else we generate an authentication URL.
 ************************************************/
if (isset($_SESSION['access_token']) && $_SESSION['access_token']) {    
      $client->setAccessToken($_SESSION['access_token']);    
} else {
  $authUrl = $client->createAuthUrl();
}

//Display user info or display login url as per the info we have.
echo '<div style="margin:20px">';   
if (isset($authUrl)){ 
	//show login url
	echo '<div align="center">';
	echo '<a class="login" href="' . $authUrl . '"><img src="images/btn_google_signin_dark_normal_web.png"/></a>';
	echo '</div>';
} else {
        try{
            $user = $service->userinfo->get(); //get user info         
        }
        catch(Google_Auth_Exception $e){
            //$uri='http://'.$_SERVER['HTTP_HOST'].dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login.php?logout=1#/';
            //header('Location: ' . filter_var($uri, FILTER_SANITIZE_URL));
            echo '<script type="text/javascript">
                      window.location.href = "/'.MY_DOMAIN.'login.php?logout=1#"
                 </script>';    
        }
        //check if user exists
        $newUser->google_id=$user->id;
        $user_count = $newUser->getCount();
		//show user picture
		//echo '<img src="'.$user->picture.'" style="float: right;margin-top: 33px; " />';
        
        if($user_count){ //if user already exist change greeting text to "Welcome Back"
        	//echo 'Welcome back '.$user->name.'! [<a href="'.$redirect_uri.'?logout=1">Log Out</a>]';
        }
        else{ //else greeting text "Thanks for registering"
            $newUser->google_id=$user->id;
            $newUser->google_name=$user->name;
            $newUser->google_email=$user->email;
            $newUser->google_link=(empty($user->link)) ? '' : $user->link;
            $newUser->google_picture_link=$user->picture;            
            $newUser->createNew();
            //echo 'Hi '.$user->name.', Thanks for Registering! [<a href="'.$redirect_uri.'?logout=1">Log Out</a>]';              
        }         
            
        //check if google user already exists
        include_once 'models/myUser.php';
        $myUser = new myUser($db);        
		$stmt = $myUser->getUserDetails($user->id);
    	
        if($stmt->rowCount()==1){
        	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);        	
        	
        	if($rows[0]['google_id']!==$user->id){
        		echo '<script type="text/javascript">
                      		window.location.href = "/'.MY_DOMAIN.'login.php?logout=1#/"
                  	  </script>';
        	}
        }
        else{
        	echo '<script type="text/javascript">
                      window.location.href = "/'.MY_DOMAIN.'login.php?logout=1#/"
                  </script>';  
        }
        
        $current_user = array(
        		'user' => array(
        				'id' => $user->id,
        				'name' => $user->name,
        				'email' => $user->email,
        				'link' => $user->link,
        				'picture' => $user->picture,
        				'shop_id'=>0,
        				'shop_name'=>'',
        				'shop_icon_url'=>'',
        				'etsy_user_id'=>0,
        				'expiry_date'=>$rows[0]['expiry_date']
        		)
        );
        $_SESSION['current_user']=json_encode($current_user);
        
        //check if etsy user already exists
        include_once 'models/etsy_user.php';
        $etsy_user = new etsy_user($db);    	
        $rows = $etsy_user->get_user_details($user->id);//google_id
        if(count($rows)>0){
            //var_dump($rows);
            echo '<script type="text/javascript">
                      window.location.href = "/'.MY_DOMAIN.'main.html"
                  </script>'; 
        }else{
            echo '<script type="text/javascript">
                      window.location.href = "/'.MY_DOMAIN.'authorize.html?'.$user->id.'"
                  </script>'; 
        }   
        
         
}

echo '</div>';