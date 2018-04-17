<?php

    // include database and object files 
    include_once 'db_connection.php'; 
    include_once 'test_renewal.php'; 
    require_once 'constants.php';
    
    // instantiate database and product object 
    $database = new db_connection();
    $db = $database->getConnection();
 
    // initialize object
    $renewal = new test_renewal($db);
    
    // query scheduled items
    $stmt = $renewal->get_scheduled_items();
    $num = $stmt->rowCount();
    
    //echo " - Scheduled items count:".$num;
    
    // check if more than 0 record found
    if($num>0){     
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            
            if(trim($row['RenewType'])!="REG"){
                $item_id=$row['ID'];                
                
                switch (trim($row['RenewType'])) {
                    case 'RND':
                        //get shop's number of listings from Etsy                        
                        $data_count=get_list_count($row['shop_id']);
                        $count=$data_count['count'];
                        echo "count=".$count;                
                        $item_array=get_unique_random_numbers_within_range(1, $count, $row['NumberOfItems']);
                        schedule_random($item_array, $row, $renewal);
                        break;
                    case 'OLD':
                        //$item_array=get_items_by_sort_param($row['shop_id'], $row['NumberOfItems'], "created", "up");
                        //schedule_oldest_or_newest($item_array, $row, $renewal);
                        schedule_oldest_or_newest($renewal, $row, "up");
                        break;
                    case 'NEW':
                        //$item_array=get_items_by_sort_param($row['shop_id'], $row['NumberOfItems'], "created", "down");
                        //schedule_oldest_or_newest($item_array, $row, $renewal);
                        schedule_oldest_or_newest($renewal, $row, "down");
                        break;
                }                
                
                if($row['RenewalStatus']==='F' OR $row['RenewalStatus']==='R'){
                    //update forever item in db
                    update_forever_item($row, $renewal);
                }
                else{
                    //mark original row as completed - $item_id renewal schedule
                    $renewal->update('D',$item_id);
                }
            }
            elseif(is_active($row['ItemID'])){

                //CALL RENEW ITEM ON ETSY!!!
                //renew_listing($row);
                
                if($row['RenewalStatus']==='F' OR $row['RenewalStatus']==='R'){
                    
                    //add completed renewal into db
                    $renewal->add_forever_completed($row['ID']);

                    //update forever item in db
                    update_forever_item($row, $renewal);
                }
                else{
                    //echo "active - renew renewal";
                    $renewal->update('C',$row['ID']);
                }              
            }else {
                //echo "sold out sweety - cancel renewal";
                $renewal->update('D',$row['ID']);
            }            
        }         
    } 
    
    function schedule_random($item_array, $row, $renewal){        
        
        foreach ($item_array as $item) {   
            //get random item from etsy
            $data=get_random($row['shop_id'], $item);                    
            $row['ItemID']=$data['results'][0]["listing_id"];

            //CALL RENEW ITEM ON ETSY!!!
            //renew_listing($row);

            $orginal_id=$row['ID'];   
            $Item_id=$data['results'][0]['listing_id'];
            $title=$data['results'][0]['title'];
            $image_url=$data['results'][0]['Images'][0]['url_75x75'];
            $quantity=$data['results'][0]['quantity'];
            $views= $data['results'][0]['views'];
            $likes=$data['results'][0]['num_favorers'];
            $epoch = $data['results'][0]['last_modified_tsz'];
            $last_updated_date = new DateTime("@$epoch");   
            $epoch = $data['results'][0]['ending_tsz'];
            $expiry_date = new DateTime("@$epoch");
            $renewal->add_scheduled_item($orginal_id, $Item_id, $title, $image_url, $quantity, $views, $likes, $last_updated_date, $expiry_date );
        }
    }
    
    function schedule_oldest_or_newest($renewal, $row, $upOrDown){  
        
        $item_array=get_items_by_sort_param($row['shop_id'], $row['NumberOfItems'], "created", $upOrDown);
        
        foreach ($item_array['results'] as $item) { 
            $row['ItemID']=$item["listing_id"];

            //CALL RENEW ITEM ON ETSY!!!
            //renew_listing($row);

            $orginal_id=$row['ID'];   
            $Item_id=$item['listing_id'];
            $title=$item['title'];
            $image_url=$item['Images'][0]['url_75x75'];
            $quantity=$item['quantity'];
            $views= $item['views'];
            $likes=$item['num_favorers'];
            $epoch = $item['last_modified_tsz'];
            $last_updated_date = new DateTime("@$epoch");   
            $epoch = $item['ending_tsz'];
            $expiry_date = new DateTime("@$epoch"); 
            $renewal->add_scheduled_item($orginal_id, $Item_id, $title, $image_url, $quantity, $views, $likes, $last_updated_date, $expiry_date );
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
    
    // RENEW THE LISTING ON  *** E T S Y ***
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
            //exit;
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
    
    function get_items_by_sort_param($shop_id, $number_of_items, $sort_on, $sort_order){    
        $url="https://openapi.etsy.com/v2/shops/".$shop_id."/listings/active?"
                . "includes=Images%28url_75x75,hex_code%29&"
                . "fields=listing_id,title,last_modified_tsz,ending_tsz,quantity,views,num_favorers&"
                . "api_key=".ETSY_API_KEY."&"
                . "sort_on=".$sort_on."&sort_order=".$sort_order."&"
                . "limit=".$number_of_items."&offset=0";    
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
        //list( $scheduled_date_time, $scheduled_date, $scheduled_time, $target_date_time, $local_date_time ) = get_schedule_dates($row);              
        list( $scheduled_date_time, $target_date_time, $local_date_time ) = get_schedule_dates($row);              
        $renewal->ScheduledDateTime=$scheduled_date_time->format('Y-m-d H:i:s');
        $renewal->ScheduledDate=$scheduled_date_time->format('Y-m-d');
        $renewal->ScheduledTime=$scheduled_date_time->format('H:i:s');
        $renewal->TargetDateTime=$target_date_time->format('Y-m-d H:i:s');
        $renewal->LocalDateTime=$local_date_time->format('Y-m-d H:i:s');
        
        $renewal->ID=$row['ID'];        
        if($row['RenewalStatus']==='R'){
            $end_date = $row["EndDate"];
            $end_time = $row["EndTime"];
            $end_date_time = new DateTime($end_date.$end_time);
            if($scheduled_date_time <= $end_date_time){
                //update the renewal
                $renewal->update_forever();
            }
            else
            {
                $renewal->delete_renewal_by_id();
                //$renewal->update('C',$row['ID']);
            }
        }
        else{
            //update the renewal
            $renewal->update_forever();
        }
    }
    
    function get_interval($unit, $timespan){   
        if ($unit=='m'){
            return '+'.$timespan.' minute';
        }elseif ($unit=='h'){
            return '+'.$timespan.' hour';    
        }elseif ($unit=='d'){
            return '+'.$timespan.' day';    
        }elseif ($unit=='w'){
            $timespan=$timespan*7;
            return '+'.$timespan.' day';    //weeks  
        }    
    }
    
    function get_schedule_dates($row){
        //current schedule values
        $schedule_date_time = new DateTime($row["ScheduledDateTime"]);
        $target_date_time = new DateTime($row["TargetDateTime"]);                    
        $local_date_time = new DateTime($row["LocalDateTime"]);        
       
        $unit=$row['Unit'];// - m=min,h=hour,d=day,w=week
        $frequency=$row['Frequency'];// - 1-20
        $mod = get_interval($unit, $frequency);
                    
        //calculate/advance next renewal schedule dates
        $schedule_date_time->modify($mod);        
        $target_date_time->modify($mod);        
        $local_date_time->modify($mod);       
        
        $scheduledDate = $schedule_date_time->format('Y-m-d');
        $scheduledTime = $schedule_date_time->format('H:i:s');
        
        $end_time = new DateTime(($row["EndTime"]));  
        if($scheduledTime > $end_time->format('H:i:s')){
            //set schedule-time from original start-time
            $scheduledTime=new DateTime(($row["StartTime"]));

            do{
               //advance days as needed
              $schedule_date_time->add(new DateInterval('P1D')); // P1D means a period of 1 day
              $scheduledDate = $schedule_date_time->format('Y-m-d');
              $dayofweek=$schedule_date_time->format('D');              
            }while($row[$dayofweek]!='Y');            
            $schedule_date_time = new DateTime($scheduledDate.$scheduledTime->format('H:i:s'));   
        }
        
        //return array( $schedule_date_time->format('Y-m-d H:i:s'), $scheduledDate, $scheduledTime, $target_date_time->format('Y-m-d H:i:s'), $local_date_time->format('Y-m-d H:i:s') );
        return array( $schedule_date_time, $target_date_time, $local_date_time );
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
        $renewal->RenewType = $db_row["RenewType"];
        // create the renewal
        $renewal->createNew();
}

    function get_unique_random_numbers_within_range($min, $max, $quantity) {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }   