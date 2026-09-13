<?php
include('helper.php');

$avatar = '/assets/images/avatar.png';
$time = time();
$o = 0;
$i = 1;
$newFlood = time() + 30;
$conn = mysqli_connect('localhost','id21046293_bdb','Toddlogan08!','id21046293_bdb');

$site = [
'Brickorzo Reborn',
'/assets/images/logo.png',
'https://brickorzo-reborn.000webhostapp.com',
'https://brickorzo-reborn.000webhostapp.com/blog',
'https://brickorzo-reborn.000webhostapp.com/admin',
'https://brickorzo-reborn.000webhostapp.com',
'/assets/images/avatar.png',
'Bucks',
'https://twitter.com/brickreborn',
'https://discord.gg/brickorzo20'
];

if(!$conn){
  die("There seems to be an error with the database connection.");
}

# Session lifetime of 3 hours
ini_set('session.gc_maxlifetime', 10800);

# Ena collection with a 1% chance of
# running on each session_start()
session_start();

function csrf(){
  $_SESSION["token"] = bin2hex(random_bytes(32));
  $_SESSION["token-expire"] = time() + 3600; 
}

function footer(){
  global $site;

  include($_SERVER['DOCUMENT_ROOT'] . '/config/footer.php');
}

function userInfo($id){
  global $conn;
  $getInfo = $conn->prepare("SELECT * FROM `brz_users` WHERE `id`=?");
  $getInfo->bind_param("i", $id);
  $getInfo->execute();
  $uInfo = $getInfo->get_result();
  $info = $uInfo->fetch_assoc();
  return $info;
}

function userInfo2($username){
  global $conn;
  $getInfo = $conn->prepare("SELECT * FROM `brz_users` WHERE `username`=?");
  $getInfo->bind_param("s", $username);
  $getInfo->execute();
  $uInfo = $getInfo->get_result();
  $info = $uInfo->fetch_assoc();
  return $info;
}

function userInfoNumU($username){
  global $conn;
  $getInfo = $conn->prepare("SELECT * FROM `brz_users` WHERE `username`=?");
  $getInfo->bind_param("s", $username);
  $getInfo->execute();
  $uInfo = $getInfo->get_result();
  return $uInfo;
}

function userInfoNumI($username){
  global $conn;
  $getInfo = $conn->prepare("SELECT * FROM `brz_users` WHERE `id`=?");
  $getInfo->bind_param("i", $username);
  $getInfo->execute();
  $uInfo = $getInfo->get_result();
  return $uInfo;
}

function threadInfo($id){
  global $conn;
  $getInfo = $conn->prepare("SELECT * FROM `forum_threads` WHERE `id`=?");
  $getInfo->bind_param("i", $id);
  $getInfo->execute();
  $uInfo = $getInfo->get_result();
  $info = $uInfo->fetch_assoc();
  return $info;
}

function friendN($u1, $u2){
  global $conn;
  $hasAdded = $conn->prepare("SELECT * FROM `friends` WHERE `sender` = ? AND `reciever` = ?");
  $hasAdded->bind_param("ii", $u1, $u2);
  $hasAdded->execute();
  $res = $hasAdded->get_result();
  return $res;
}

function GetItem($id){
  global $conn;
  $getItems = $conn->prepare("SELECT * FROM `items_brz` WHERE `id`=?") or die($conn->error);
  $getItems->bind_param("i", $id) or die($conn->error);
  $getItems->execute() or die($conn->error);
  $itemResult = $getItems->get_result() or die($conn->error);
  return $itemResult;
}

function getLimiteds($id){
  global $conn;
  $status = "limited";
  $getItems = $conn->prepare("SELECT * FROM `items_brz` WHERE `id`=? AND `status`=?") or die($conn->error);
  $getItems->bind_param("is", $id, $status) or die($conn->error);
  $getItems->execute() or die($conn->error);
  $itemResult = $getItems->get_result() or die($conn->error);
  return $itemResult;
}

function getItemOwners($id){
  global $conn;
  $getRes = $conn->prepare("SELECT * FROM `brz_inv` WHERE `item` = ?") or die($conn->error);
  $getRes->bind_param('i', $id) or die($conn->error);
  $getRes->execute() or die($conn->error);
  $Result = $getRes->get_result() or die($conn->error);
  return $Result;
}

if(isset($_SESSION['id'])){

  $userCheck = $conn->prepare("SELECT * FROM `brz_users` WHERE `id`=?");
  $userCheck->bind_param("i", $_SESSION['id']);
  $userCheck->execute();
  $UCheck = $userCheck->get_result();

  if(mysqli_num_rows($UCheck) == 0){
    session_start();
    session_destroy();
    unset($_SESSION['id']);
    unset($_SESSION['admin']);
    header("Location: /index.php");
  }

  $user = $UCheck->fetch_assoc();

  $online = $conn->prepare("UPDATE `brz_users` SET `last_online`=? WHERE `id`=?");
  $online->bind_param("ii", $time, $user['id']);
  $online->execute();

  switch($user['rank']){
    case '0':
      $rank = "User";
      break;
    case '1':
      $rank = "Asset Creator";
      break;
    case '2':
      $rank = "Moderator";
      break;
    case '3':
      $rank = "Economy Manager";
      break;
    case '4':
      $rank = "Administrator";
      break;
    case '5':
      $rank = "Executive Administrator";
      break;
    default:
      $rank = "???";
  }

  if($user['rank'] != 0){
    $checkAdminBadge = $conn->query("SELECT * FROM `badges` WHERE `badge_id`='1' AND `user_id`='$user[id]'");
    if($checkAdminBadge->num_rows == 0){
      $giveAdminBadge = $conn->prepare("INSERT INTO `badges` VALUES(NULL,?,?)");
      $giveAdminBadge->bind_param("ii", $i, $user['id']);
      $giveAdminBadge->execute();
    }
  }

  if($user['shards'] >= 500){
    $two = 2;
    $checkAdminBadge = $conn->query("SELECT * FROM `badges` WHERE `badge_id`='2' AND `user_id`='$user[id]'");
    if($checkAdminBadge->num_rows == 0){
      $giveAdminBadge = $conn->prepare("INSERT INTO `badges` VALUES(NULL,?,?)");
      $giveAdminBadge->bind_param("ii", $two, $user['id']);
      $giveAdminBadge->execute();
    }
  }

  if($user['membership'] != 0){
    $two = 4;
    $checkPlusBadge = $conn->query("SELECT * FROM `badges` WHERE `badge_id`='4' AND `user_id`='$user[id]'");
    if($checkPlusBadge->num_rows == 0){
      $givePlusBadge = $conn->prepare("INSERT INTO `badges` VALUES(NULL,?,?)");
      $givePlusBadge->bind_param("ii", $two, $user['id']);
      $givePlusBadge->execute();
    }
  }

  if(!preg_match("/\/games\/test\//i", $_SERVER['REQUEST_URI'])){
    $deleteAllSessions = $conn->prepare("DELETE FROM `brz_game_joins` WHERE `user_id`=?");
    $deleteAllSessions->bind_param("i", $user['id']);
    $deleteAllSessions->execute();
  }
  
  if(preg_match("/\/admin\//", $_SERVER['REQUEST_URI'])){
    if((!isset($_SESSION['id'])) || ($user['rank'] == 0)){
     die(); 
    }
  }

  if(($user["membership"] != 0) && ($user["membership_expire"] < $time)){
    $removeMembership = $conn->prepare("UPDATE `brz_users` SET `membership`=?, `membership_expire`=? WHERE `id`=?");
    $removeMembership->bind_param("iii", $o, $o, $user["id"]);
    $removeMembership->execute();
    
    $msgContent = ["Your membership has expired.", "You can purchase an extra month over at $site[5]/upgrade/. Or you can stay as a regular user."];
    $letUserKnow = $conn->prepare("INSERT INTO `msgs_brz` (`id`, `title`, `body`, `sender`, `reciever`, `read`, `time`) VALUES (NULL,?,?,?,?,?,?)");
    $letUserKnow->bind_param("ssiiii", $msgContent[0], $msgContent[1], $i, $user["id"], $o, $time);
    $letUserKnow->execute();
    
    $badgeId = 4;
    $deleteBadge = $conn->prepare("DELETE FROM `badges` WHERE `badge_id`=? AND `user_id`=?");
    $deleteBadge->bind_param("ii", $badgeId, $user["id"]);
    $deleteBadge->execute();
  }
  
  if($user['membership'] != 0){
    $checkPlusHat = $conn->query("SELECT * FROM `brz_inv` WHERE `item`='6' AND `buyer`='$user[id]'");
    $checkPlusTool = $conn->query("SELECT * FROM `brz_inv` WHERE `item`='7' AND `buyer`='$user[id]'");
    if(($checkPlusHat->num_rows == 0) && ($checkPlusTool->num_rows == 0)){
      $id = ['6','7'];
      $ggUBoughtMembershipDumbFuck = $conn->prepare("INSERT INTO `brz_inv` VALUES(NULL,?,?,?)") or die(mysqli_error($conn));
      $ggUBoughtMembershipDumbFuck->bind_param("iii", $id[0], $user['id'], $o) or die(mysqli_error($conn));
      $ggUBoughtMembershipDumbFuck->execute() or die(mysqli_error($conn));
      $ggUBoughtMembershipDumbFuck = $conn->prepare("INSERT INTO `brz_inv` VALUES(NULL,?,?,?)") or die(mysqli_error($conn));
      $ggUBoughtMembershipDumbFuck->bind_param("iii", $id[1], $user['id'], $o) or die(mysqli_error($conn));
      $ggUBoughtMembershipDumbFuck->execute() or die(mysqli_error($conn));
    }
  }

  /* $checkReggular = $conn->query("SELECT * FROM `brz_inv` WHERE `item`='6' AND `buyer`='$user[id]'");
  $checkUkraine = $conn->query("SELECT * FROM `brz_inv` WHERE `item`='7' AND `buyer`='$user[id]'");
  if($user['membership'] != 0){
    if(($checkReggular->num_rows == 0) && ($checkUkraine->num_rows == 0)){
      $id = ['6','7'];
      $ggUrDoneWithTheEggHunt = $conn->prepare("INSERT INTO `brz_inv` VALUES(NULL,?,?,?)") or die(mysqli_error($conn));
      $ggUrDoneWithTheEggHunt->bind_param("iii", $id[0], $user['id'], $o) or die(mysqli_error($conn));
      $ggUrDoneWithTheEggHunt->execute() or die(mysqli_error($conn));
      $ggUrDoneWithTheEggHunt = $conn->prepare("INSERT INTO `brz_inv` VALUES(NULL,?,?,?)") or die(mysqli_error($conn));
      $ggUrDoneWithTheEggHunt->bind_param("iii", $id[1], $user['id'], $o) or die(mysqli_error($conn));
      $ggUrDoneWithTheEggHunt->execute() or die(mysqli_error($conn));
    }
  }*/
}
?>