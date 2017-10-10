<?php
    include_once 'constants.php';
    
    session_start();
    
    if (isset($_SESSION['current_user']) && $_SESSION['current_user']) {
    
        echo '<a class="login" href="etsy_oauth_request.php">'
                    . '<button id="authorize-me" class="btn btn-large btn-danger" type="button" >AUTHORIZE ACCESS TO YOUR SHOP</button>'
           . '</a>'; 
        echo '</br>';
        echo '</br>';
        echo '<a class="login" href="/'.MY_DOMAIN.'login.php?logout=1#/">'
                    . '<button id="authorize-me" class="btn btn-large btn-primary" type="button" >CANCEL</button>'
           . '</a>'; 

    }else{
    
        echo '<script type="text/javascript">        		
              	window.location.href = "/'.MY_DOMAIN.'login.php?logout=1#/"                
             </script>';  
    }