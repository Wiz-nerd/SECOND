<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');

if(!isset($_SESSION['id'])){
  die(header("Location: /login/")); 
}

$checkIfGameExists = $conn->prepare("SELECT * FROM `brz_games` WHERE `id`=?");
$checkIfGameExists->bind_param("i", $_GET['id']);
$checkIfGameExists->execute();
$gameCheckResults = $checkIfGameExists->get_result();

$checkIfTokenValid = $conn->prepare("SELECT * FROM `brz_game_joins` WHERE `user_id`=? AND `game_id`=? AND `token`=?");
$checkIfTokenValid->bind_param("iis", $user['id'], $_GET['id'], $_GET['token']);
$checkIfTokenValid->execute();
$tokenCheckResults = $checkIfTokenValid->get_result();

if((!isset($_GET['id']) || $_GET['id'] == null || $gameCheckResults->num_rows == 0) || (!isset($_GET['token']) || $_GET['token'] == null || $tokenCheckResults->num_rows == 0)){
  die(header("Location: /error/code/404")); 
}

$checkIfPlayerIsntMulti = $conn->prepare("SELECT * FROM `brz_game_joins` WHERE `user_id`=?");
$checkIfPlayerIsntMulti->bind_param("i", $user['id']);
$checkIfPlayerIsntMulti->execute();
$multiIngameCheckResults = $checkIfPlayerIsntMulti->get_result();

if($multiIngameCheckResults->num_rows > 1){
  $deleteAllSessions = $conn->prepare("DELETE FROM `brz_game_joins` WHERE `user_id`=?");
  $deleteAllSessions->bind_param("i", $user['id']);
  $deleteAllSessions->execute();
  die(header("Location: /games/"));
}

$getCurrentPlayers = $conn->prepare("SELECT * FROM `brz_game_joins` WHERE `game_id`=?");
$getCurrentPlayers->bind_param("i", $_GET['id']);
$getCurrentPlayers->execute();
$allPlayers = $getCurrentPlayers->get_result();

$game = $gameCheckResults->fetch_assoc();
?>