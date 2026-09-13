<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
if(isset($_POST['gameId'])){

  if(!isset($_SESSION['id'])){
    die("error3"); 
  }

  $checkIfGameExists = $conn->prepare("SELECT * FROM `brz_games` WHERE `id`=?");
  $checkIfGameExists->bind_param("i", $_POST['gameId']);
  $checkIfGameExists->execute();
  $gameCheckResults = $checkIfGameExists->get_result();

  $checkIfPlayerIsntMulti = $conn->prepare("SELECT * FROM `brz_game_joins` WHERE `user_id`=?");
  $checkIfPlayerIsntMulti->bind_param("i", $user['id']);
  $checkIfPlayerIsntMulti->execute();
  $multiIngameCheckResults = $checkIfPlayerIsntMulti->get_result();

  if($gameCheckResults->num_rows == 0){
    die("error2");
  }

  if($multiIngameCheckResults->num_rows > 1){
    die("error1");
  }

  if((getOS() == "iPhone") || (getOS() == "iPad") || (getOS() == "iPod") || (getOS() == "Android") || (getOS() == "BlackBerry") || (getOS() == "Mobile")){
    die("error4");
  }

  function generateRandomString($length = 50) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
  }
  
  $token = generateRandomString();
  $x_pos = 30;
  $y_pos = 230.11;

  $startGameSession = $conn->prepare("INSERT INTO `brz_game_joins` VALUES(NULL,?,?,?,?)");
  $startGameSession->bind_param("iiis", $user['id'], $_POST['gameId'], $time, $token);
  $startGameSession->execute();
  $increaseVisitCount = $conn->prepare("UPDATE `brz_games` SET `visits` = `visits` + 1 WHERE `id`=?");
  $increaseVisitCount->bind_param("i", $_POST['gameId']);
  $increaseVisitCount->execute();
  die("/games/test/?id=$_POST[gameId]&token=$token");

}
?>