<?php
//echo mt_rand() . "\n";
//echo mt_rand() . "\n";
include_once 'constants.php';

//1)todo: return shop_id from get_scheduled_items method
$shop_id="6923405";

//2)get items count for the shop from Etsy
$data=get_list_count($shop_id);
$count=$data['count'];
echo "count=".$count;

//3)calc random
$random=mt_rand(0, $count-1);
echo "random=".$random;

//4)get random item from etsy
$data=get_random($shop_id, $random);
$item_id=$data['results'][0]["listing_id"];
echo "item_id=".$item_id;

//5)update the random row in db with actual listing item

//6)call renew item on etsy


//$item_id="254649643";

if(is_active($item_id)){
    echo "renew";
}else {
    echo "SOLD OUT Sweety!";
    echo "cancel renewal";
}

//check if item wasn't sold out
function is_active($item_id){
    $data=get_item_by_id($item_id);
    $quantity= $data['results'][0]["quantity"];
    $state=$data['results'][0]["state"];
    return ($quantity>0 && $state==="active");
}

function get_list_count($shop_id){
    $url="https://openapi.etsy.com/v2/shops/".$shop_id."/listings/active?fields=listing_id&api_key=".ETSY_API_KEY."&limit=1&offset=0";
    return http_get($url);
}


function get_item_by_id($item_id){
    $url="https://openapi.etsy.com/v2/listings/".$item_id."?fields=quantity,state&api_key=".ETSY_API_KEY; 
    return http_get($url);
}


function get_random($shop_id, $random){
    
    $url="https://openapi.etsy.com/v2/shops/".$shop_id."/listings/active?"
                . "includes=Images%28url_75x75,hex_code%29&"
                . "fields=listing_id,title,last_modified_tsz,ending_tsz,price,quantity,views,num_favorers&"
                . "api_key=".ETSY_API_KEY."&"
                . "sort_on=created&sort_order=up&"
                . "limit=1&offset=".$random;
    
    return http_get($url);
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
	
	function getItembyID($item_id){
		//https://openapi.etsy.com/v2/shops/yutal/listings/active?fields=listing_id,state&api_key=wh5yhq82mbfntus5nkby8yti
		//https://openapi.etsy.com/v2/listings/254649644?includes=Images%28url_75x75,hex_code%29&fields=listing_id,title,last_modified_tsz,ending_tsz,price,quantity,views,num_favorers,state&api_key=wh5yhq82mbfntus5nkby8yti
		//{"count":1,"results":[{"quantity":0,"state":"sold_out"}],"params":{"listing_id":"254649643"},"type":"Listing","pagination":{}}
		
		
		
	}
	