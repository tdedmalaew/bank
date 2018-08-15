<?php
require "Transaction.php";

$obj = new Transaction();
echo json_encode($obj->getAmount());
?>