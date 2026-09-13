<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(!isset($_SESSION['id'])){
  die(header("Location: /login/"));
}

if(isset($_POST['username'])){
  $bio = $_POST['username'];

  if(strlen($bio) < 3 || strlen($bio) > 23){
    die("Username has to be between 2-23 characters");
  }

  $regex = '/\\A[a-z\\d]+(?:[.-][a-z\\d]+)*\\z/i';
  if(!preg_match_all($regex, $bio)){
    die('Invalid User Syntax.');
  }

  if($user['diamonds'] < 10){
    die("You do not have enough diamonds!"); 
  }

  $checkIfNameIsTaken = $conn->query("SELECT * FROM `brz_users` WHERE `username`='$bio'");
  if($checkIfNameIsTaken->num_rows != 0){
    die("Username is already taken"); 
  }

  $checkPastName = $conn->query("SELECT * FROM `past_names` WHERE `username`='$bio'");
  if($checkPastName->num_rows != 0){
    die("Username is already taken"); 
  }

  $price = 10;
  $addToPastNames = $conn->prepare("INSERT INTO `past_names` (`id`,`username`,`user_id`) VALUES(NULL,?,?)") or die($conn->error);
  $addToPastNames->bind_param("si", $user["username"], $user["id"]) or die($conn->error);
  $addToPastNames->execute() or die($conn->error);
  $stmt = $conn->prepare("UPDATE `brz_users` SET `username`=?, `diamonds`= `diamonds` - ? WHERE `id`=?");
  $stmt->bind_param("sii", $bio, $price, $user['id']);
  $stmt->execute();
  die("Successfully changed username!");
}
?>