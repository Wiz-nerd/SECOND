<?php
include("{$_SERVER["DOCUMENT_ROOT"]}/config/param.php");
if(isset($_POST['name'])){

  $getUserAvatar = $conn->prepare("SELECT * FROM `brz_avatar` WHERE `user_id`=?");
  $getUserAvatar->bind_param("i", $user['id']);
  $getUserAvatar->execute();
  $avatarResult = $getUserAvatar->get_result();
  $avatar = $avatarResult->fetch_assoc();

  if($user['membership'] == 0){
    $limit = 5;
  }else{
    $limit = 30;
  }
  
  $getUserOutfits = $conn->prepare("SELECT * FROM `av_outfits_orzo` WHERE `user`=?");
  $getUserOutfits->bind_param("i", $user['id']);
  $getUserOutfits->execute();
  $outfitResult = $getUserOutfits->get_result();
  if($outfitResult->num_rows >= $limit){
    die("You cannot create anymore than $limit outfits");
  }

  $regex = '/\\A[a-z\\d]+(?:[.-][a-z\\d]+)*\\z/i';
  if(!preg_match_all($regex, $_POST['name'])){
    die("Invalid name syntax");
  }

  if(strlen($_POST['name']) < 3 || strlen($_POST['name']) > 16){
    die("Your outfit name must be between 3-16 characters");
  }

  $insertOutfit = $conn->prepare("INSERT INTO `av_outfits_orzo` (`id`, `user`, `name`, `image`, `hat`, `hat2`, `hat3`, `hat4`, `hat5`, `shirt`, `pant`, `tool`, `face`, `head_color`, `torso_color`, `right_arm_color`, `left_arm_color`, `right_leg_color`, `left_leg_color`) VALUES(NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)") or die($conn->error);
  $insertOutfit->bind_param("issiiiiiiiiissssss", $user["id"], $_POST["name"], $user["avatar_img"], $avatar["hat"], $avatar["hat2"], $avatar["hat3"], $avatar["hat4"], $avatar["hat5"], $avatar["shirt"], $avatar["pant"], $avatar["tool"], $avatar["face"], $avatar["head_color"], $avatar["torso_color"], $avatar["right_arm_color"], $avatar["left_arm_color"], $avatar["right_leg_color"], $avatar["left_leg_color"]);
  $insertOutfit->execute();
  die("succ");
}

if(isset($_POST['delete']) && isset($_POST['id'])){
  $getUserOutfits = $conn->prepare("SELECT * FROM `av_outfits_orzo` WHERE `id`=? AND `user`=?");
  $getUserOutfits->bind_param("ii", $_POST['id'], $user['id']);
  $getUserOutfits->execute();
  $outfitResult = $getUserOutfits->get_result();
  if($outfitResult->num_rows == 0){
    die("The outfit you want to delete does not exist");
  }
  
  $deleteOutfit = $conn->prepare("DELETE FROM `av_outfits_orzo` WHERE `id`=? AND `user`=?");
  $deleteOutfit->bind_param("ii", $_POST['id'], $user['id']);
  $deleteOutfit->execute();
  die("succ");
}
?>