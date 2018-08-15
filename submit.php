<?php 
	require "Transaction.php";

	$obj = new Transaction();
	$money = $_GET["money"];
	$result = $obj->transfer(1, 2, $money);
	echo json_encode($result);
 ?>