<?php

    session_start(); //session start
    
    include_once 'constants.php';
    
    $shop_name= $_GET['shopname'];
    
    //echo 'shop name:'.$shop_name;
    
    function httpGet($shop_name, $offset){
        
        $url="https://openapi.etsy.com/v2/shops/".$shop_name."/listings/active?"
                . "includes=Images%28url_75x75,hex_code%29&"
                . "fields=listing_id,title,last_modified_tsz,ending_tsz,price,quantity,views,num_favorers&"
                . "api_key=".ETSY_API_KEY."&"
                . "sort_on=created&sort_order=up&"
                . "limit=50&offset=".$offset;

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
    
    $offset=0;
    $listing_array=array();
    $j=0;
    $response_body=httpGet($shop_name, $offset);    
    $data = json_decode($response_body, TRUE);
    $listing_count=count($data['results']);
    while($listing_count > 0) {
        for($i=0; $i<count($data['results']); $i++) {
            $j=$j+1;
            $epoch = $data['results'][$i]["last_modified_tsz"];
            $last_modified_dt = new DateTime("@$epoch");        
            $epoch = $data['results'][$i]["ending_tsz"];
            $expiry_dt = new DateTime("@$epoch");
            array_push($listing_array, array(
                    "num"=>$j,
                    "ItemID"=>$data['results'][$i]["listing_id"],
                    "selected"=>false,
                    "Title"=>$data['results'][$i]["title"],
                    "Quantity"=>$data['results'][$i]["quantity"],
                    "Views"=>$data['results'][$i]["views"],
                    "Likes"=>$data['results'][$i]["num_favorers"],
                    "Price"=>$data['results'][$i]["price"],
                    "LastUpdatedDate"=>$last_modified_dt->format('Y-m-d H:i:s'),
                    "ExpiryDate"=>$expiry_dt->format('Y-m-d H:i:s'),
                    "ImageUrl"=>$data['results'][$i]["Images"][0]["url_75x75"]
                ));
        }         
        $offset=$offset+50;
        $response_body=httpGet($shop_name, $offset);    
        $data = json_decode($response_body, TRUE);
        $listing_count=count($data['results']);
    }
    $post_data =array('listings'=>$listing_array);
    // json format output 
    echo (json_encode($post_data));    