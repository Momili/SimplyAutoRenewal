<?php 

// get database connection 
include_once 'db_connection.php'; 
$database = new db_connection(); 
$db = $database->getConnection();
 
// instantiate product object
include_once 'renewal.php';
$renewal = new renewal($db);

$id= $_GET['id'];
$renewal->update('C', $id); //set status=Cancel

//echo "OK";