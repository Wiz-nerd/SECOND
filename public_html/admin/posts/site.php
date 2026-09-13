<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(!isset($_SESSION['id'])){
 die('You are not logged in'); 
}
//banner updating
if(isset($_POST['banner']) || isset($_POST['color'])){
$stmt = $conn->prepare("UPDATE `brz_site_conf` SET `banner`=?, `banner_color`=?");
$stmt->bind_param("ss", $_POST['banner'], $_POST['color']);
$stmt->execute();
die("Successfully updated banner!");
}

//maintenance updating
if(isset($_POST['maintain']) || isset($_POST['code'])){
$getSiteSettings = $conn->prepare("SELECT * FROM `brz_site_conf`");
$getSiteSettings->execute();
$setResult = $getSiteSettings->get_result();
$settings = $setResult->fetch_assoc();

if($_POST['maintain'] == $settings['maintenance']){
	die("Brickorzo is either already in maintenance or already public");
}

$stmt = $conn->prepare("UPDATE `brz_site_conf` SET `maintenance`=?, `maintenance_code`=?");
$stmt->bind_param("ss", $_POST['maintain'], $_POST['code']);
$stmt->execute();
die("Successfully updated site!");
}