<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$colorsToHex = [
  "#FF0000" => "red",
  "#FFB57C" => "beige",
  "#0040FF" => "blue",
  "#FF6A00" => "orange",
  "#FFFF00" => "yellow",
  "#111111" => "black",
  "#824629" => "brown",
  "#FFFFFF" => "white"
];

$getUserAvatar = $conn->prepare("SELECT * FROM `brz_avatar` WHERE `user_id`=?");
$getUserAvatar->bind_param("i", $user['id']);
$getUserAvatar->execute();
$avatarResult = $getUserAvatar->get_result();
$avatar = $avatarResult->fetch_assoc();

function colorToHex($limb){
  global $colorsToHex;

  $color = array_search($limb, $colorsToHex);
  return $color;
}
?>

<div onclick="openColorModal('headColor')" style="height:50px;margin:auto;width:50px;background-color:<?=colorToHex($avatar['head_color']);?>;" id="headColor"></div>
<div onclick="openColorModal('rightArmColor')" style="float:left;height:100px;width:50px;background-color:<?=colorToHex($avatar['right_arm_color']);?>;" id="leftArmColor"></div>
<div onclick="openColorModal('torsoColor')" style="float:left;height:100px;width:100px;background-color:<?=colorToHex($avatar['torso_color']);?>;" id="torsoColor"></div>
<div onclick="openColorModal('leftArmColor')" style="float:left;height:100px;width:50px;background-color:<?=colorToHex($avatar['left_arm_color']);?>;" id="rightArmColor"></div>
<div onclick="openColorModal('leftLegColor')" style="margin-left:50px;float:left;height:100px;width:50px;background-color:<?=colorToHex($avatar['left_leg_color']);?>;" id="leftLegColor"></div>
<div onclick="openColorModal('rightLegColor')" style="float:left;height:100px;width:50px;background-color:<?=colorToHex($avatar['right_leg_color']);?>;" id="rightLegColor"></div>