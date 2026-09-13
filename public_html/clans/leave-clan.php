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
    die("The clan you are trying to leave doesn't exist");
  }

  $clan = $clanResult->fetch_assoc();
  $getMembers = $conn->prepare("SELECT * FROM `brz_clan_members` WHERE `clan_id`=? AND `user_id`=?");
  $getMembers->bind_param("ii", $_POST['clan'], $user['id']);
  $getMembers->execute();
  $memberResult = $getMembers->get_result();

  if($memberResult->num_rows == 0){
    die("You have already left this clan");
  }

  if($clan['owner'] == $user['id']){
    die("You cannot leave your own clan");
  }

  $leaveClan = $conn->prepare("DELETE FROM `brz_clan_members` WHERE `user_id`=? AND `clan_id`=?");
  $leaveClan->bind_param("ii", $user['id'], $_POST['clan']);
  $leaveClan->execute();

  $newCount = $clan['members'] - 1;
  $updateClan = $conn->prepare("UPDATE `brz_clans` SET `members`=? WHERE `id`=?");
  $updateClan->bind_param("ii", $newCount, $_POST['clan']);
  $updateClan->execute();

  die("success");
}