<?php

include_once 'db_connection.php'; 
include_once 'test_renewal.php'; 
require_once 'constants.php';

    date_default_timezone_set("America/Los_Angeles");
    echo "The time is " . date("h:i:sa");
    echo date('Y-m-d H:i:s');
    
    // get database connection 
    include_once 'db_connection.php'; 
    $database = new db_connection(); 
    $db = $database->getConnection();

    $testRenewal = new test_renewal($db);
    
    
    $testRenewal->delete_all_renewals();
    
    $testRenewal->UserID = '106099307187031694788';        
    $testRenewal->ShopID='7360282';
    $testRenewal->ItemID = '253698946';
    $testRenewal->Title =  'Science tights dark, perfect graduation gift, mathematics, biology gift';
    $testRenewal->ImageUrl = 'https:\/\/img1.etsystatic.com\/112\/1\/7360282\/il_75x75.1006799463_h8co.jpg';
    $testRenewal->Quantity = 6;
    $testRenewal->Views = 1524;
    $testRenewal->Likes = 283;
    $testRenewal->LastUpdatedDate = '2017-06-21 15:30:16';
    $testRenewal->ExpiryDate = '2017-10-21 15:30:16';
        
    $testRenewal->RenewalStatus = 'F';
    $testRenewal->ScheduledDateTime = date('Y-m-d H:i:s');//$data['renewals'][$i]["ScheduledDateTime"];//$data->ScheduledDate;
    $testRenewal->ScheduledDate = date('Y-m-d');//$data['renewals'][$i]["ScheduledDate"];//$data->ScheduledDate;
    $testRenewal->ScheduledTime = date('H:i:s');//$data['renewals'][$i]["ScheduledTime"];//$data->ScheduledTime;
    $testRenewal->UpdatedTimeStamp = date('Y-m-d H:i:s');
    $testRenewal->TargetDateTime = date('Y-m-d H:i:s');//$data['renewals'][$i]["TargetDateTime"];
    $testRenewal->LocalDateTime = date('Y-m-d H:i:s');//$data['renewals'][$i]["LocalDateTime"];
    
    $testRenewal->Unit = 'm'; //h- hour, m- minute, d- day
    $testRenewal->Frequency = '30'; //1-30
    $testRenewal->RenewType = 'REG'; //REG,RND,OLD,NEW
    
    $testRenewal->EndDate=date('Y-m-d');
    $testRenewal->EndTime=date('H:i:s');
    $testRenewal->NumberOfItems=1;
    $testRenewal->Sun='N';
    $testRenewal->Mon='N';
    $testRenewal->Tue='N';
    $testRenewal->Wed='N';
    $testRenewal->Thu='N';
    $testRenewal->Fri='N';
    $testRenewal->Sat='N';
    $testRenewal->TargetTZ=-1;
    $testRenewal->LocalTZ=-2;
    
    // create the renewal
    $testRenewal->createNew();
    
    $testRenewal->UserID = '106099307187031694788';        
    $testRenewal->ShopID='7360282';
    $testRenewal->ItemID = '478129888';
    $testRenewal->Title =  'Golden flower tights';
    $testRenewal->ImageUrl = 'https:\/\/img1.etsystatic.com\/163\/1\/7360282\/il_75x75.1128137405_hv1q.jpg';
    $testRenewal->Quantity = 6;
    $testRenewal->Views = 131;
    $testRenewal->Likes = 30;
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
    $testRenewal->RenewType = 'REG'; //REG,RND,OLD,NEW
    
    $testRenewal->EndDate=date('Y-m-d');
    $testRenewal->EndTime=date('H:i:s');
    $testRenewal->NumberOfItems=1;
    $testRenewal->Sun='N';
    $testRenewal->Mon='N';
    $testRenewal->Tue='N';
    $testRenewal->Wed='N';
    $testRenewal->Thu='N';
    $testRenewal->Fri='N';
    $testRenewal->Sat='N';
    $testRenewal->TargetTZ=-1;
    $testRenewal->LocalTZ=-2;
    
    // create the renewal
    $testRenewal->createNew();
    
    $testRenewal->UserID = '106099307187031694788';        
    $testRenewal->ShopID='7360282';
    $testRenewal->ItemID = '164893613';
    $testRenewal->Title =  'Ombre tights salmon - purple gradient tights';
    $testRenewal->ImageUrl = 'https:\/\/img0.etsystatic.com\/136\/1\/7360282\/il_75x75.932763990_5kap.jpg';
    $testRenewal->Quantity = 4;
    $testRenewal->Views = 4534;
    $testRenewal->Likes = 1324;
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
    $testRenewal->RenewType = 'REG'; //REG,RND,OLD,NEW
    
    $testRenewal->EndDate=date('Y-m-d');
    $testRenewal->EndTime=date('H:i:s');
    $testRenewal->NumberOfItems=1;
    $testRenewal->Sun='N';
    $testRenewal->Mon='N';
    $testRenewal->Tue='N';
    $testRenewal->Wed='N';
    $testRenewal->Thu='N';
    $testRenewal->Fri='N';
    $testRenewal->Sat='N';
    $testRenewal->TargetTZ=-1;
    $testRenewal->LocalTZ=-2;
    
    // create the renewal
    $testRenewal->createNew();
    
    //RANDOM item
    $testRenewal->UserID = '106099307187031694788';        
    $testRenewal->ShopID='7360282';
    $testRenewal->ItemID = '1';
    $testRenewal->Title =  'Random Item';
    $testRenewal->ImageUrl = '';
    $testRenewal->Quantity = 1;
    $testRenewal->Views = 1;
    $testRenewal->Likes = 1;
    $testRenewal->LastUpdatedDate = '2010-06-21 15:30:16';
    $testRenewal->ExpiryDate = '2010-10-21 15:30:16';
        
    $testRenewal->RenewalStatus = 'F';
    $testRenewal->ScheduledDateTime = date('Y-m-d H:i:s');//$data['renewals'][$i]["ScheduledDateTime"];//$data->ScheduledDate;
    $testRenewal->ScheduledDate = date('Y-m-d');//$data['renewals'][$i]["ScheduledDate"];//$data->ScheduledDate;
    $testRenewal->ScheduledTime = date('H:i:s');//$data['renewals'][$i]["ScheduledTime"];//$data->ScheduledTime;
    $testRenewal->UpdatedTimeStamp = date('Y-m-d H:i:s');
    $testRenewal->TargetDateTime = date('Y-m-d H:i:s');//$data['renewals'][$i]["TargetDateTime"];
    $testRenewal->LocalDateTime = date('Y-m-d H:i:s');//$data['renewals'][$i]["LocalDateTime"];
    
    $testRenewal->Unit = 'd'; //h- hour, m- minute, d- day
    $testRenewal->Frequency = '1'; //1-30
    $testRenewal->RenewType = 'RND'; //REG,RND,OLD,NEW
    
    $testRenewal->EndDate=date('Y-m-d');
    $testRenewal->EndTime=date('H:i:s');
    $testRenewal->NumberOfItems=5;
    $testRenewal->Sun='N';
    $testRenewal->Mon='N';
    $testRenewal->Tue='N';
    $testRenewal->Wed='N';
    $testRenewal->Thu='N';
    $testRenewal->Fri='N';
    $testRenewal->Sat='N';
    $testRenewal->TargetTZ=-1;
    $testRenewal->LocalTZ=-2;
    
    // create the renewal
    $testRenewal->createNew();
    
    //least recent (old)
    $testRenewal->UserID = '106099307187031694788';        
    $testRenewal->ShopID='7360282';
    $testRenewal->ItemID = '1';
    $testRenewal->Title =  'Least Recent';
    $testRenewal->ImageUrl = '';
    $testRenewal->Quantity = 0;
    $testRenewal->Views = 0;
    $testRenewal->Likes = 0;
    $testRenewal->LastUpdatedDate = '2010-06-21 15:30:16';
    $testRenewal->ExpiryDate = '2010-10-21 15:30:16';
        
    $testRenewal->RenewalStatus = 'F';
    $testRenewal->ScheduledDateTime = date('Y-m-d H:i:s');//$data['renewals'][$i]["ScheduledDateTime"];//$data->ScheduledDate;
    $testRenewal->ScheduledDate = date('Y-m-d');//$data['renewals'][$i]["ScheduledDate"];//$data->ScheduledDate;
    $testRenewal->ScheduledTime = date('H:i:s');//$data['renewals'][$i]["ScheduledTime"];//$data->ScheduledTime;
    $testRenewal->UpdatedTimeStamp = date('Y-m-d H:i:s');
    $testRenewal->TargetDateTime = date('Y-m-d H:i:s');//$data['renewals'][$i]["TargetDateTime"];
    $testRenewal->LocalDateTime = date('Y-m-d H:i:s');//$data['renewals'][$i]["LocalDateTime"];
    
    $testRenewal->Unit = 'h'; //h- hour, m- minute, d- day
    $testRenewal->Frequency = '2'; //1-30
    $testRenewal->RenewType = 'OLD'; //REG,RND,OLD,NEW
    
    $testRenewal->EndDate=date('Y-m-d');
    $testRenewal->EndTime=date('H:i:s');
    $testRenewal->NumberOfItems=5;
    $testRenewal->Sun='N';
    $testRenewal->Mon='N';
    $testRenewal->Tue='N';
    $testRenewal->Wed='N';
    $testRenewal->Thu='N';
    $testRenewal->Fri='Y';
    $testRenewal->Sat='N';
    $testRenewal->TargetTZ=-1;
    $testRenewal->LocalTZ=-2;
    
    // create the renewal
    $testRenewal->createNew();
    
    //most recent (new)
    $testRenewal->UserID = '106099307187031694788';        
    $testRenewal->ShopID='7360282';
    $testRenewal->ItemID = '1';
    $testRenewal->Title =  'Most Recent';
    $testRenewal->ImageUrl = '';
    $testRenewal->Quantity = 0;
    $testRenewal->Views = 0;
    $testRenewal->Likes = 0;
    $testRenewal->LastUpdatedDate = '2010-06-21 15:30:16';
    $testRenewal->ExpiryDate = '2010-10-21 15:30:16';
        
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
    $testRenewal->NumberOfItems=3;
    $testRenewal->Sun='N';
    $testRenewal->Mon='N';
    $testRenewal->Tue='N';
    $testRenewal->Wed='N';
    $testRenewal->Thu='N';
    $testRenewal->Fri='N';
    $testRenewal->Sat='N';
    $testRenewal->TargetTZ=-1;
    $testRenewal->LocalTZ=-2;
    
    // create the renewal
    $testRenewal->createNew();