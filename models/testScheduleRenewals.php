<?php

// include database and object files 
/*
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


$schedule_date_time = date('Y-m-d H:i:s');
echo "before";
echo $schedule_date_time;
$max_schedule_date_time=$schedule_date_time;
echo $max_schedule_date_time;

echo "after";
$schedule_date_time->format('+'.$timespan.' day');

echo $schedule_date_time;
echo $max_schedule_date_time;
*/
$schedule_date_time = new DateTime(date('Y-m-d H:i:s'));
echo $schedule_date_time;