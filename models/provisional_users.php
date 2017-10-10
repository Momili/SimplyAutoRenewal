<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
        
$opts = array('http' =>
    array(
        'method'  => 'POST',
        'header'  => 'x-api-key:wh5yhq82mbfntus5nkby8yti',
    )
);
$context  = stream_context_create($opts);
$result = file_get_contents('https://openapi.etsy.com/v3/application/provisional-users/21885378', false, $context);
echo $result;