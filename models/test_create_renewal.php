<?php

include_once 'db_connection.php'; 
include_once 'test_renewal.php'; 
require_once 'constants.php';

    
    // get database connection 
    include_once 'db_connection.php'; 
    $database = new db_connection(); 
    $db = $database->getConnection();

    $testRenewal = new test_renewal($db);
    
    $testRenewal->UserID = '7002018';        
    $testRenewal->ShopID='5486190';
    $testRenewal->ItemID = '29621006';
    $testRenewal->Title =  'The Ball';
    $testRenewal->ImageUrl = 'https:\/\/img1.etsystatic.com\/000\/0\/5486190\/il_75x75.86052581.jpg';
    $testRenewal->Quantity = 1;
    $testRenewal->Views = 894;
    $testRenewal->Likes = 26;
    $testRenewal->LastUpdatedDate = '2017-06-21 15:30:16';
    $testRenewal->ExpiryDate = '2017-10-21 15:30:16';
        
    $testRenewal->RenewalStatus = 'S';
    $testRenewal->ScheduledDateTime = date('Y-m-d H:i:s');//$data['renewals'][$i]["ScheduledDateTime"];//$data->ScheduledDate;
    $testRenewal->ScheduledDate = date('Y-m-d');//$data['renewals'][$i]["ScheduledDate"];//$data->ScheduledDate;
    $testRenewal->ScheduledTime = date('H:i:s');//$data['renewals'][$i]["ScheduledTime"];//$data->ScheduledTime;
    $testRenewal->UpdatedTimeStamp = date('Y-m-d H:i:s');
    $testRenewal->TargetDateTime = date('Y-m-d H:i:s');//$data['renewals'][$i]["TargetDateTime"];
    $testRenewal->LocalDateTime = date('Y-m-d H:i:s');//$data['renewals'][$i]["LocalDateTime"];
    
    $testRenewal->Unit = 'd'; //h- hour, m- minute, d- day
    $testRenewal->Frequency = '1'; //1-30
    $testRenewal->RenewType = 'NEW'; //REG,RND,OLD,NEW
    
    $testRenewal->EndDate=date('Y-m-d');
    $testRenewal->EndTime=date('H:i:s');
    $testRenewal->NumberOfItems=5;
    $testRenewal->Sun='Y';
    $testRenewal->Mon='N';
    $testRenewal->Tue='N';
    $testRenewal->Wed='N';
    $testRenewal->Thu='N';
    $testRenewal->Fri='N';
    $testRenewal->Sat='Y';
    $testRenewal->TargetTZ=-1;
    $testRenewal->LocalTZ=-2;
    
    // create the renewal
    $testRenewal->createNew();