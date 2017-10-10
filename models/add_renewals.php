<?php 
session_start(); //session start

header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json; charset=UTF-8"); 

// get database connection 
include_once 'db_connection.php'; 
$database = new db_connection(); 
$db = $database->getConnection();
 
// instantiate product object
include_once 'renewal.php';
$renewal = new renewal($db);

if (isset($_SESSION['current_user']) && $_SESSION['current_user']) {
    $current_user = $_SESSION['current_user'];
    $user_data=json_decode($current_user, TRUE);
    $userId=$user_data["user"]["id"];
    $shopId=$user_data["user"]["shop_id"];
    $expiry_date=new DateTime($user_data["user"]["expiry_date"]);    
}else{
    exit;
}    

//echo $expiry_date;
if($expiry_date < new DateTime(date('Y-m-d H:i:s'))){
    echo json_encode(array( 'status'=> 'expired','expiry_date' => $expiry_date->format('Y-m-d') ));
    exit;
}

$json= $_GET['listing'];
//$json='{"renewals":[{"ItemID":"11","selected":true,"Title":"Beautiful ring","ImageUrl":"https://img0.etsystatic.com/000/0/5486190/il_75x75.254911376.jpg","LastUpdatedDate":"2016-01-17","ScheduledDate":"2016-04-10","ScheduledTime":"11:35:00","RenewalStatus":"A","ShopID":"1","$$hashKey":"object:4"},{"ItemID":"22","selected":true,"Title":"Very nice ring","ImageUrl":"https://img0.etsystatic.com/018/0/5486190/il_75x75.577441034_5ady.jpg","LastUpdatedDate":"2015-12-28","ScheduledDate":"2016-04-10","ScheduledTime":"11:35:00","RenewalStatus":"A","ShopID":"1","$$hashKey":"object:5"},{"ItemID":"33","selected":true,"Title":"Fantastic earring","ImageUrl":"https://img1.etsystatic.com/054/0/5486190/il_75x75.726412499_mid3.jpg","LastUpdatedDate":"2015-12-28","ScheduledDate":"2016-04-10","ScheduledTime":"11:35:00","RenewalStatus":"A","ShopID":"1","$$hashKey":"object:6"}]}';

$data = json_decode($json, TRUE);

//calc recurrence 
$repeat = $data['recurrence'][0]['repeat'];
$schedule_date_time = new DateTime($data['renewals'][0]["ScheduledDateTime"]);
$target_date_time = new DateTime($data['renewals'][0]["TargetDateTime"]);
$local_date_time = new DateTime($data['renewals'][0]["LocalDateTime"]);

//repeat number of times
if ($repeat==2){
    $frequency= intval($data['recurrence'][0]['frequency']);
    $unit=intval($data['recurrence'][0]['unit']);   
    $times= $data['recurrence'][0]['times'];
    
    //check if max schedule date is not after expiry date
    $max_schedule_date_time=new DateTime($schedule_date_time->format('Y-m-d H:i:s'));    
    $max_mod = get_interval($unit, $frequency);
    for($n=0; $n < $times-1; $n++) {
        $max_schedule_date_time->modify($max_mod);        
    }
    
    //$max_schedule_date_time->modify('-'.$frequency.' day');    
    //echo 'max = '.$max_schedule_date_time->format('Y-m-d H:i:s').'\n';
    //echo 'expiry = '.$expiry_date->format('Y-m-d H:i:s').'\n';
    //echo '2. schedule = '.$schedule_date_time->format('Y-m-d H:i:s').'\n';
    
    if($expiry_date->format('Y-m-d H:i:s') < $max_schedule_date_time->format('Y-m-d H:i:s')) {
        echo json_encode(array( 'status'=> 'expired','expiry_date' => $expiry_date->format('Y-m-d') ));
        exit;
    }
    else {
        echo json_encode(array( 'status'=> 'OK','expiry_date' => $expiry_date->format('Y-m-d') ));
    }
    
    //OK - start scheduling
    $mod = get_interval($unit, $frequency);
    for($n=0; $n <= $times-1; $n++) {
        //echo "frequency=".$frequency;
        //echo "mod=".$mod;
        add_renewal($renewal, $data, $userId, $shopId, $schedule_date_time, $target_date_time, $local_date_time, $unit, $frequency);
        $schedule_date_time->modify($mod);        
        $target_date_time->modify($mod);        
        $local_date_time->modify($mod);        
        //echo $schedule_date_time->format('Y-m-d H:i:s');
        //echo $schedule_date_time->format('Y-m-d');
        //echo $schedule_date_time->format('H:i:s');
        //echo '</br>';
    }    
}else{
    
    if($expiry_date->format('Y-m-d H:i:s') < $schedule_date_time->format('Y-m-d H:i:s')) {
       echo json_encode(array( 'status'=> 'expired','expiry_date' => $expiry_date->format('Y-m-d') ));
       exit;
    }
    else {
        echo json_encode(array( 'status'=> 'OK','expiry_date' => $expiry_date->format('Y-m-d') ));
    }
    
    add_renewal($renewal, $data, $userId, $shopId, $schedule_date_time, $target_date_time, $local_date_time,0,0);
}



function add_renewal($renewal, $data, $userId, $shopId, $schedule_date_time, $target_date_time, $local_date_time,$unit,$frequency){
    
    for($i=0; $i<count($data['renewals']); $i++) {    
        //echo '*****'.$data['renewals'][$i]["TargetDateTime"].'******';//$data['renewals'][$i]["UserID"];    
        $renewal->UserID = $userId;//$data['renewals'][$i]["UserID"];//$data->ShopID;
        $renewal->ShopID = $shopId;//$data['renewals'][$i]["UserID"];//$data->ShopID;
        $renewal->ItemID = $data['renewals'][$i]["ItemID"];//$data->ItemID;
        $renewal->Title =  $data['renewals'][$i]["Title"];//$data->Title;
        $renewal->ImageUrl = $data['renewals'][$i]["ImageUrl"];//$data->ImageUrl;
        $renewal->Quantity = $data['renewals'][$i]["Quantity"];//$data->ImageUrl;
        $renewal->Views = $data['renewals'][$i]["Views"];//$data->ImageUrl;
        $renewal->Likes = $data['renewals'][$i]["Likes"];//$data->ImageUrl;
        $renewal->LastUpdatedDate = $data['renewals'][$i]["LastUpdatedDate"];//$data->LastUpdatedDate;
        $renewal->ExpiryDate = $data['renewals'][$i]["ExpiryDate"];//$data->LastUpdatedDate;
        $renewal->RenewalStatus = $data['renewals'][$i]["RenewalStatus"];//$data->RenewalStatus;
        $renewal->ScheduledDateTime = $schedule_date_time->format('Y-m-d H:i:s');//$data['renewals'][$i]["ScheduledDateTime"];//$data->ScheduledDate;
        $renewal->ScheduledDate = $schedule_date_time->format('Y-m-d');//$data['renewals'][$i]["ScheduledDate"];//$data->ScheduledDate;
        $renewal->ScheduledTime = $schedule_date_time->format('H:i:s');//$data['renewals'][$i]["ScheduledTime"];//$data->ScheduledTime;
        $renewal->UpdatedTimeStamp = date('Y-m-d H:i:s');
        $renewal->TargetDateTime = $target_date_time->format('Y-m-d H:i:s');//$data['renewals'][$i]["TargetDateTime"];
        $renewal->LocalDateTime = $local_date_time->format('Y-m-d H:i:s');//$data['renewals'][$i]["LocalDateTime"];
        $renewal->Unit = $unit;
        $renewal->Frequency = $frequency;
        $renewal->RenewType = 'REG';
        // create the renewal
        $renewal->createNew();
    } 
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