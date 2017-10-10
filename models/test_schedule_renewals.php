<?php

    // include database and object files 
    include_once 'db_connection.php'; 
    include_once 'renewal.php'; 
    require_once 'constants.php';
    
    // instantiate database and product object 
    $database = new db_connection();
    $db = $database->getConnection();
 
    // initialize object
    $renewal = new renewal($db);
    
    // query scheduled items
    $stmt = $renewal->get_scheduled_items();
    $num = $stmt->rowCount();
    
    echo "scheduled items count:".$num;
    
    // check if more than 0 record found
    if($num>0){     
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            if(trim($row['Title'])==="Random Item"){
                //get items count for the shop from Etsy
                $shop_id=$row['shop_id'];
                $data_count=get_list_count($shop_id);
                $count=$data_count['count'];
                echo "count=".$count;

                //calc a random number
                $random=mt_rand(0, $count-1);
                echo "random=".$random;

                //get random item from etsy
                $data=get_random($shop_id, $random);
                $item_id=$data['results'][0]["listing_id"];
                echo "item_id=".$item_id;
                
                $row['ItemID']=$item_id;
                echo "row['ItemID']".$row['ItemID'];
                
                //call renew item on etsy
                renew_listing($row);
                
                echo "RenewalStatus=".$row['RenewalStatus'];
                
                if($row['RenewalStatus']==='F'){
                    
                    //add completed renewal into db
                    add_renewal($renewal, $data['results'][0], $row);                    
                    
                    //update forever item in db
                    update_forever_item($row, $renewal); 
                }
                else{
                    //update the random item's row in db with an actual listing item
                    echo "not forever!";
                    update_random_item($data['results'][0], $row['ID'], $renewal);  
                }
            }
            elseif(is_active($row['ItemID'])){
                echo "renew on etsy";
                renew_listing($row);
                
                if($row['RenewalStatus']==='F'){
                    
                    //add completed renewal into db
                    $renewal->add_forever_completed($row['ID']);

                    //update forever item in db
                    update_forever_item($row, $renewal);     
                    
                }
                else{
                    echo "active - renew renewal";
                    $renewal->update('R',$row['ID']);
                }
            }else {
                echo "sold out - cancel renewal";
                $renewal->update('C',$row['ID']);
            }            
        }         
    } 
    
    function is_active($item_id){
        //echo $item_id;
        $data=get_item_by_id($item_id);
        $quantity= $data['results'][0]["quantity"];
        $state=$data['results'][0]["state"];
        return ($quantity>0 && $state==="active");
}

    function get_item_by_id($item_id){
        $url="https://openapi.etsy.com/v2/listings/".$item_id."?fields=quantity,state&api_key=".ETSY_API_KEY; 
        return http_get($url);
    }
    
    // renew listing on Etsy
    function renew_listing($row){ 
        
        $access_token = $row['access_token'];
        $access_token_secret = $row['access_token_secret'];
        $listing_id=$row['ItemID'];
        
        $oauth = new OAuth(ETSY_API_KEY, ETSY_SHARED_SECRET, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
        
        $oauth->disableSSLChecks();
        
        $oauth->setToken($access_token, $access_token_secret);

        $url = ETSY_API_URL.'/listings/'.$listing_id.'?renew=true';        
        //echo $url;
                
        try {
            $data = $oauth->fetch($url, null, OAUTH_HTTP_METHOD_PUT);
            
            //var_dump($data);
            //$json = $oauth->getLastResponse();
            //var_dump((json_decode($json, true)) );

        } catch (OAuthException $e) {
            //TODO: LOG ERRORS
            echo "error=".$e->getMessage();
            print($e->getMessage());
            print(print_r($oauth->getLastResponse(), true));
            print(print_r($oauth->getLastResponseInfo(), true));
            exit;
        }
    }   
    
    function http_get($url){
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
        return json_decode($response_body, TRUE);
    }
}

    function get_list_count($shop_id){
        $url="https://openapi.etsy.com/v2/shops/".$shop_id."/listings/active?fields=listing_id&api_key=".ETSY_API_KEY."&limit=1&offset=0";
        return http_get($url);
    }
    
    function get_random($shop_id, $random){    
        $url="https://openapi.etsy.com/v2/shops/".$shop_id."/listings/active?"
                . "includes=Images%28url_75x75,hex_code%29&"
                . "fields=listing_id,title,last_modified_tsz,ending_tsz,quantity,views,num_favorers&"
                . "api_key=".ETSY_API_KEY."&"
                . "sort_on=created&sort_order=up&"
                . "limit=1&offset=".$random;    
        return http_get($url);
    }
 
    function update_random_item($data, $id, $renewal){
        
        $renewal->ItemID = $data["listing_id"];
        $renewal->Title =  $data["title"];
        $renewal->ImageUrl = $data["Images"][0]["url_75x75"];
        $renewal->Quantity = $data["quantity"];
        $renewal->Views = $data["views"];
        $renewal->Likes = $data["num_favorers"];
        $epoch = $data["last_modified_tsz"];
        $last_modified_dt = new DateTime("@$epoch");
        $renewal->LastUpdatedDate = $last_modified_dt->format('Y-m-d H:i:s');
        $epoch = $data["ending_tsz"];
        $expiry_dt = new DateTime("@$epoch");        
        $renewal->ExpiryDate = $expiry_dt->format('Y-m-d H:i:s');
        $renewal->UpdatedTimeStamp = date('Y-m-d H:i:s');        
        $renewal->RenewType='RND';
        $renewal->RenewalStatus='R';
        $renewal->ID=$id;        
        //update the renewal
        $renewal->update_random();
    }
    
    function update_forever_item($row, $renewal){        
        
        $renewal->UpdatedTimeStamp = date('Y-m-d H:i:s');        
        
        //calc next renewal date/time        
        list( $scheduled_date_time, $scheduled_date, $scheduled_time, $target_date_time, $local_date_time )=get_schedule_dates($row);              
        $renewal->ScheduledDateTime=$scheduled_date_time;
        $renewal->ScheduledDate=$scheduled_date;
        $renewal->ScheduledTime=$scheduled_time;
        $renewal->TargetDateTime=$target_date_time;
        $renewal->LocalDateTime=$local_date_time;
        
        $renewal->ID=$row['ID'];        
        //update the renewal
        $renewal->update_forever();
    }
    
    function get_interval($unit, $timespan){   
        if ($unit=='1'){
            return '+'.$timespan.' minute';
        }elseif ($unit=='2'){
            return '+'.$timespan.' hour';    
        }elseif ($unit=='3'){
            return '+'.$timespan.' day';    
        }elseif ($unit=='4'){
            $timespan=$timespan*7;
            return '+'.$timespan.' day';    //weeks  
        }    
}
    
    function get_schedule_dates($row){
        //current schedule values
        $schedule_date_time = new DateTime($row["ScheduledDateTime"]);
        $target_date_time = new DateTime($row["TargetDateTime"]);                    
        $local_date_time = new DateTime($row["LocalDateTime"]);
        
        //echo "before schedule_date_time=".$schedule_date_time->format('Y-m-d H:i:s');
        //echo "before target_date_time=".$target_date_time->format('Y-m-d H:i:s');
        //echo "before local_date_time=".$local_date_time->format('Y-m-d H:i:s');
                    
        $unit=$row['Unit'];// - 1=min,2=hour,3=day,4=week
        $frequency=$row['Frequency'];// - 1-20
        $mod = get_interval($unit, $frequency);
                    
        //calculate next renewal schedule dates
        $schedule_date_time->modify($mod);        
        $target_date_time->modify($mod);        
        $local_date_time->modify($mod);     
        
        $scheduledDate = $schedule_date_time->format('Y-m-d');
        $scheduledTime = $schedule_date_time->format('H:i:s');

        return array( $schedule_date_time->format('Y-m-d H:i:s'), $scheduledDate, $scheduledTime, $target_date_time->format('Y-m-d H:i:s'), $local_date_time->format('Y-m-d H:i:s') );
    }
    
    function add_renewal($renewal, $etsy_data, $db_row){    
        $renewal->UserID = $db_row["UserID"];
        $renewal->ShopID = $db_row["shop_id"];
        $renewal->ItemID = $etsy_data["listing_id"];
        $renewal->Title =  $etsy_data["title"];
        $renewal->ImageUrl = $etsy_data["Images"][0]["url_75x75"];
        $renewal->Quantity = $etsy_data["quantity"];
        $renewal->Views = $etsy_data["views"];
        $renewal->Likes = $etsy_data["num_favorers"];

        $epoch = $etsy_data["last_modified_tsz"];
        $last_modified_dt = new DateTime("@$epoch");        
        $renewal->LastUpdatedDate = $last_modified_dt->format('Y-m-d H:i:s');
        
        $epoch = $etsy_data["ending_tsz"];
        $expiry_dt = new DateTime("@$epoch");
        $renewal->ExpiryDate = $expiry_dt->format('Y-m-d H:i:s');
        
        $renewal->RenewalStatus = 'R';
        $renewal->ScheduledDateTime = $db_row["ScheduledDateTime"];
        $renewal->ScheduledDate = $db_row["ScheduledDate"];
        $renewal->ScheduledTime = $db_row["ScheduledTime"];
        $renewal->TargetDateTime = $db_row["TargetDateTime"];
        $renewal->LocalDateTime = $db_row["LocalDateTime"];
        $renewal->Unit = $db_row["Unit"];
        $renewal->Frequency = $db_row["Frequency"];
        $renewal->RenewType = 'RND';
        // create the renewal
        $renewal->createNew();
}

