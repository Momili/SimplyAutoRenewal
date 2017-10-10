<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$d1=new DateTime('Sun Jun 05 2016 18:45:00');
echo $d1->format('Y-m-d H:i:s');
echo '</br>';

$d2 = new DateTime();
//$di=  get_interval(2, 1);
//echo $di;
//$d2->add($di);
echo $d2->format('Y-m-d H:i:s');
echo '</br>';
$mod=get_interval(4,2);
echo $mod;
echo '</br>';
$d2->modify($mod);
echo $d2->format('Y-m-d H:i:s');


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