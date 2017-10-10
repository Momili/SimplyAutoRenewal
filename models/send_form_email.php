<?php

header("Access-Control-Allow-Origin: *"); 
header("Content-Type: application/json; charset=UTF-8"); 

$json= $_GET['msg'];
$data = json_decode($json, TRUE);

echo $data['from'];
echo $data['subject'];
echo $data['comments'];

$to = "braggin.dragons@gmail.com";
$subject = $data['subject'];
$message = $data['comments'];
//$message = wordwrap($message, 70, "\r\n");
$headers = "From: ".$data['from'];
mail($to, $subject, $message, $headers); 