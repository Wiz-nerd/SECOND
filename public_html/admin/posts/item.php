<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(isset($_POST['search'])){

	$search = $_POST['search'];

	$getItem = $conn->prepare("SELECT * FROM `items_brz` WHERE `item_name`=?");
	$getItem->bind_param("s", $_POST['search']);
	$getItem->execute();
	$itemResult = $getItem->get_result();

	if($itemResult->num_rows == 0){
		die("Item does not exist");
	}

	die("succ");
}