<?php
include("{$_SERVER['DOCUMENT_ROOT']}/config/param.php");
$getClan = $conn->prepare("SELECT * FROM `brz_clans` WHERE `id`=? AND `approved`=?");
$getClan->bind_param("ii", $_GET['id'], $i);
$getClan->execute();
$clanResult = $getClan->get_result();

if(!isset($_GET['id']) || $_GET['id'] == null || $clanResult->num_rows == 0 || !is_numeric($_GET['id'])){
	header("Location: /error/code/404");
}

$clan = $clanResult->fetch_assoc();

if($clan['owner'] != $user['id']){
	header("Location: /error/code/404");
}
?>