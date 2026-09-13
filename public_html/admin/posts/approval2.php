<?php
$i = 1;
$o = 0;
session_start();
$conn = mysqli_connect('localhost','id21046293_bdb','Toddlogan08!','id21046293_bdb');
if(isset($_POST['request'])){
  switch($_POST['request']){
    case "accept":
      $e = $conn->query("SELECT * FROM `brz_clans` WHERE `id`='$_POST[id]'");
      $item = $e->fetch_assoc();

      if($item['approved'] == 1){
        die("e1"); 
      }

      $defaultRanks = ['Member', 'Administrator', 'Owner'];
      $clanImage = "/assets/images/clans/".$_POST['id'].".png";
      $insertMemberRank = $conn->prepare("INSERT INTO `brz_clan_ranks` VALUES(NULL,?,?,?,?,?,?)") or die(mysqli_error($conn));
      $insertMemberRank->bind_param("siiiii", $defaultRanks[0], $item['id'], $i, $o, $o, $o) or die(mysqli_error($conn));
      $insertMemberRank->execute() or die(mysqli_error($conn));
      $insertAdminRank = $conn->prepare("INSERT INTO `brz_clan_ranks` VALUES(NULL,?,?,?,?,?,?)") or die(mysqli_error($conn));
      $insertAdminRank->bind_param("siiiii", $defaultRanks[1], $item['id'], $o, $i, $o, $o) or die(mysqli_error($conn));
      $insertAdminRank->execute() or die(mysqli_error($conn));
      $insertOwnerRank = $conn->prepare("INSERT INTO `brz_clan_ranks` VALUES(NULL,?,?,?,?,?,?)") or die(mysqli_error($conn));
      $insertOwnerRank->bind_param("siiiii", $defaultRanks[2], $item['id'], $o, $i, $i, $i) or die(mysqli_error($conn));
      $insertOwnerRank->execute() or die(mysqli_error($conn));
      $ownerRank = $conn->insert_id;
      $addOwnerToClan = $conn->prepare("INSERT INTO `brz_clan_members` VALUES(NULL,?,?,?)") or die(mysqli_error($conn));
      $addOwnerToClan->bind_param("iii", $item['owner'], $_POST['id'], $ownerRank) or die(mysqli_error($conn));
      $addOwnerToClan->execute() or die(mysqli_error($conn));
      $makeClanPublic = $conn->prepare("UPDATE `brz_clans` SET `approved`=1, `icon`=?, `members`= `members` + 1 WHERE `id`=?") or die(mysqli_error($conn));
      $makeClanPublic->bind_param("si", $clanImage, $item['id']) or die(mysqli_error($conn));
      $makeClanPublic->execute() or die(mysqli_error($conn));
      die($conn->insert_id);
      break;
    case "decline":
      $e = $conn->query("SELECT * FROM `brz_clans` WHERE `id`='$_POST[id]'");
      $item = $e->fetch_assoc();

      if($item['approved'] == 1){
        die("e1"); 
      }

      $stmt = $conn->prepare("DELETE FROM `brz_clans` WHERE `id`=?");
      $stmt->bind_param("i", $item['id']);
      $stmt->execute();
      die($conn->insert_id);
      break;
  }
}