<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(!isset($_SESSION['id'])){
  die(header("Location: /login/"));
}

if(isset($_POST['bio']) && isset($_POST['token'])){
  $bio = $_POST['bio'];

  if(!isset($_SESSION["token"]) || !isset($_SESSION["token-expire"])){
    die("Token is not set.");
  }

  if ($_SESSION["token"] == $_POST["token"]) {
    if (time() >= $_SESSION["token-expire"]) {
      die("Token expired. Please reload the form.");
    } else {
      unset($_SESSION["token"]);
      unset($_SESSION["token-expire"]);
    }
  } else { die("Invalid CSRF token."); }


  if(strlen($bio) < 4 || strlen($bio) > 999){
    die("Bio must be between 3-999 characters");
  }

  /*$regex = '/canada/i';
  if(preg_match($regex, $bio)){
    $id = 223;
    $insertItem = $conn->prepare("INSERT INTO `brz_inv` VALUES(NULL,?,?,?)");
    $insertItem->bind_param("iii", $id, $user["id"], $o);
    $insertItem->execute();
    die("Successfully obtained the Antlers Of Decision!");
  }*/

  $stmt = $conn->prepare("UPDATE `brz_users` SET `bio`=? WHERE `id`=?");
  $stmt->bind_param("si", $bio, $user['id']);
  $stmt->execute();
  die("Successfully updated bio!");
}
?>