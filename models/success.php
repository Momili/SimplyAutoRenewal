<?php

session_start(); //session start

include_once 'constants.php';

if (isset($_SESSION['current_user']) && $_SESSION['current_user']) {
	$current_user = $_SESSION['current_user'];
	$user_data=json_decode($current_user, TRUE);
	$user_id=$user_data["user"]["id"];
	$shop_id=$user_data["user"]["shop_id"];
	$description=$_SESSION["Description"];
	$months=$_SESSION["Months"];
	$payment_amount=$_SESSION["Payment_Amount"];
	$start_date=$_SESSION["Start_Date"];
	$end_date=$_SESSION["End_Date"];
}else{
	exit;
}

$post_data = array(
		'payment' => array(
				'description' => $description,
				'months' => $months,
				'payment_amount' => $payment_amount,
				'start_date' => $start_date,
				'end_date' => $end_date
		)
);

echo json_encode($post_data);