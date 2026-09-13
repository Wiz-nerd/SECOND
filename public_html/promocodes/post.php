<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
if(isset($_POST['code'])){
$getCode = $conn->prepare("SELECT * FROM `brz_promo` WHERE `code`=?");
$getCode->bind_param("s", $_POST['code']);
$getCode->execute();
$codeResult = $getCode->get_result();

if(mysqli_num_rows($codeResult) == 0){
	die("Promocode doesn't exist");
}

$code = $codeResult->fetch_assoc();

if($code['active'] != 1){
	die("Promocode has expired");
}

$hasRedeemedQ = $conn->prepare("SELECT * FROM `brz_promo_logs` WHERE `code` =? AND `redeemer` =?");
$hasRedeemedQ->bind_param("ii", $code['id'], $user['id']);
$hasRedeemedQ->execute();
$hasRedeemedS = $hasRedeemedQ->get_result();

if(mysqli_num_rows($hasRedeemedS) != 0){
	die("You have already redeemed this promocode");
}

$checkItem = $conn->prepare("SELECT * FROM `brz_inv` WHERE `item`=? AND `buyer`=?");
$checkItem->bind_param("ii", $code['item'], $user['id']);
$checkItem->execute();
$itemResult = $checkItem->get_result();

if(mysqli_num_rows($itemResult) != 0){
	die("You already own this item");
}

$stmt = $conn->prepare("INSERT INTO `brz_promo_logs` VALUES(NULL,?,?)");
$stmt->bind_param("ii", $code['id'], $user['id']);
$stmt->execute();

$serial = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `item`='$code[item]'")) + 1;

$giveItem = $conn->prepare("INSERT INTO `brz_inv` VALUES(NULL,?,?,?)");
$giveItem->bind_param("iii", $code['item'], $user['id'], $serial);
$giveItem->execute();

die("Successfully redeemed item!");
}