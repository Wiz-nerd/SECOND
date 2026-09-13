<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
include($_SERVER['DOCUMENT_ROOT'] . '/avatar/testt/index.php');

if(!isset($_SESSION['id'])){
  die("You are not authenticated"); 
}

if(isset($_POST['request'])){
  $getUserAvatar = $conn->prepare("SELECT * FROM `brz_avatar` WHERE `user_id`=?");
  $getUserAvatar->bind_param("i", $user['id']);
  $getUserAvatar->execute();
  $avatarResult = $getUserAvatar->get_result();

  if($avatarResult->num_rows == 0){
    $stmt = $conn->prepare("INSERT INTO `brz_avatar` (`user_id`,`hat`) VALUES(NULL, ?)");
    $stmt->bind_param("i", $o);
    $stmt->execute();
  }

  $avatar = $avatarResult->fetch_assoc();

  switch($_POST['request']){
    case "redraw":

      break;
    case "reset":
      $resetAvatar = $conn->prepare("UPDATE `brz_avatar` SET `hat`=0,`hat2`=0,`hat3`=0,`hat4`=0,`hat5`=0,`face`=0,`shirt`=0,`pant`=0,`tool`=0 WHERE `user_id`=?");
      $resetAvatar->bind_param("i", $user['id']);
      $resetAvatar->execute();
      break;
    case "render":
      $id = $_POST['id'];
      $finditem = mysqli_query($conn,"SELECT * FROM `items_brz` WHERE `id` = '$id'");
      $item = mysqli_fetch_array($finditem);
      $brz_invQuery = mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `buyer`='$user[id]' AND `item`='$id'");
      if(mysqli_num_rows($brz_invQuery) < 1){
        die();
      }

      switch($item['type']){
        case 'hat':
          $equipHat = mysqli_query($conn,"UPDATE `brz_avatar` SET `hat` = '$id' WHERE `user_id` = '$user[id]'");
          $equipHat2 = mysqli_query($conn,"UPDATE `brz_avatar` SET `hat2` = '$avatar[hat]' WHERE `user_id` = '$user[id]'");
          $equipHat3 = mysqli_query($conn,"UPDATE `brz_avatar` SET `hat3` = '$avatar[hat2]' WHERE `user_id` = '$user[id]'");
          $equipHat4 = mysqli_query($conn,"UPDATE `brz_avatar` SET `hat4` = '$avatar[hat3]' WHERE `user_id` = '$user[id]'");
          $equipHat5 = mysqli_query($conn,"UPDATE `brz_avatar` SET `hat5` = '$avatar[hat4]' WHERE `user_id` = '$user[id]'");
          break;
        case 'face':
          $equipFace = mysqli_query($conn,"UPDATE `brz_avatar` SET `face` = '$id' WHERE `user_id` = '$user[id]'") or die(mysqli_error());
          break;
        case 'tool':
          $equipTool = mysqli_query($conn,"UPDATE `brz_avatar` SET `tool` = '$id' WHERE `user_id` = '$user[id]'") or die(mysqli_error());
          break;
        case 'shirt':
          $equipShirt = mysqli_query($conn,"UPDATE `brz_avatar` SET `shirt` = '$id' WHERE `user_id` = '$user[id]'") or die(mysqli_error());
          break;
        case 'pant':
          $equipPant = mysqli_query($conn,"UPDATE `brz_avatar` SET `pant` = '$id' WHERE `user_id` = '$user[id]'") or die(mysqli_error());
          break;
        default:
          die();
      }
      break;
    case "remove":
      $id = $_POST['id'];
      mysqli_query($conn,"UPDATE `brz_avatar` SET `$id` = '0' WHERE `user_id` = '$user[id]'");
      break;
    case "color":
      $color = $_POST['color'];
      $limb = $_POST['limb'];
      $table = $limb . "_color";
      $updateBodyColors = mysqli_query($conn,"UPDATE `brz_avatar` SET `$table` = '$color' WHERE `user_id` = '$user[id]'") or die("Invalid body color");
      break;
    case "outfit":
      $id = $_POST['id'];
      $getOutfit = $conn->query("SELECT * FROM `av_outfits_orzo` WHERE `id`='$id' AND `user`='$user[id]'") or die("Invalid outfit");
      $outfit = $getOutfit->fetch_assoc();
      $equipOutfit = $conn->prepare("UPDATE `brz_avatar` SET `hat`=?,`hat2`=?,`hat3`=?,`hat4`=?,`hat5`=?,`face`=?,`shirt`=?,`pant`=?,`tool`=?,`head_color`=?,`torso_color`=?,`right_arm_color`=?,`left_arm_color`=?,`right_leg_color`=?,`left_leg_color`=? WHERE `user_id`=?");
      $equipOutfit->bind_param("iiiiiiiiissssssi", $outfit['hat'], $outfit['hat2'], $outfit['hat3'], $outfit['hat4'], $outfit['hat5'], $outfit['face'], $outfit['shirt'], $outfit['pant'], $outfit['tool'], $outfit['head_color'], $outfit['torso_color'], $outfit['right_arm_color'], $outfit['left_arm_color'], $outfit['right_leg_color'], $outfit['left_leg_color'], $user['id']);
      $equipOutfit->execute();
      break;
    default:
      die("Invalid Request");
  }
  $image->addLimb("head", $avatar['head_color']);
  $image->addLimb("torso", $avatar['torso_color']);
  $image->addLimb("right_arm", $avatar['right_arm_color']);
  $image->addLimb("left_arm", $avatar['left_arm_color']);
  $image->addLimb("right_leg", $avatar['right_leg_color']);
  $image->addLimb("left_leg", $avatar['left_leg_color']);
  if($avatar['shirt'] != 0){
    $image->addItem($avatar['shirt']);
  }
  if($avatar['face'] != 0){
    $image->addItem($avatar['face']);
  }else{
    $image->addItem(999999999); 
  }
  if($avatar['hat5'] != 0){
    $image->addItem($avatar['hat5']);
  }
  if($avatar['hat4'] != 0){
    $image->addItem($avatar['hat4']);
  }
  if($avatar['hat3'] != 0){
    $image->addItem($avatar['hat3']);
  }
  if($avatar['hat2'] != 0){
    $image->addItem($avatar['hat2']);
  }
  if($avatar['hat'] != 0){
    $image->addItem($avatar['hat']);
  }
  if($avatar['pant'] != 0){
    $image->addItem($avatar['pant']);
  }
  if($avatar['tool'] != 0){
    $image->addItem($avatar['tool']);
  }
  $random = rand(0, 9999999);
  $image->save($random);
  $newIMG = "/assets/images/avatars/".$random.".png";
  $updateAvatar = $conn->prepare("UPDATE `brz_users` SET `avatar_img`=? WHERE `id`=?") or die(mysqli_error($conn));
  $updateAvatar->bind_param("si", $newIMG, $user['id']) or die(mysqli_error($conn));
  $updateAvatar->execute() or die(mysqli_error($conn));
  die("success");
}

?>