<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(isset($_POST['clan'])){

  	if(!isset($_SESSION['id'])){
     die("You are not logged in"); 
    }
  
	$getClan = $conn->prepare("SELECT * FROM `brz_clans` WHERE `id`=? AND `approved`=?");
	$getClan->bind_param("ii", $_POST['clan'], $i);
	$getClan->execute();
	$clanResult = $getClan->get_result();

	if($clanResult->num_rows == 0){
		die("The clan you are trying to join doesn't exist");
	}

	$clan = $clanResult->fetch_assoc();
	$getMembers = $conn->prepare("SELECT * FROM `brz_clan_members` WHERE `clan_id`=? AND `user_id`=?");
	$getMembers->bind_param("ii", $_POST['clan'], $user['id']);
	$getMembers->execute();
	$memberResult = $getMembers->get_result();

	if($memberResult->num_rows == 1){
		die("You have already joined this clan");
	}

	$getDefaultRank = $conn->prepare("SELECT * FROM `brz_clan_ranks` WHERE `clan_id`=? AND `default`=? ORDER BY `id` DESC LIMIT 1") or die(mysqli_error($conn));
	$getDefaultRank->bind_param("ii", $_POST['clan'], $i) or die(mysqli_error($conn));
	$getDefaultRank->execute() or die(mysqli_error($conn));
	$rank = $getDefaultRank->get_result()->fetch_assoc() or die(mysqli_error($conn));

	$joinClan = $conn->prepare("INSERT INTO `brz_clan_members` VALUES(NULL,?,?,?)");
	$joinClan->bind_param("iii", $user['id'], $_POST['clan'], $rank['id']);
	$joinClan->execute();

	$newCount = $clan['members'] + 1;
	$updateClan = $conn->prepare("UPDATE `brz_clans` SET `members`=? WHERE `id`=?");
	$updateClan->bind_param("ii", $newCount, $_POST['clan']);
	$updateClan->execute();

	die("success");
}