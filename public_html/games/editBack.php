<?php
include("{$_SERVER['DOCUMENT_ROOT']}/config/param.php");
if(isset($_POST['id']) && isset($_POST['desc']) || isset($_FILES['img']['tmp_name'])){

  $checkIfGameExists = $conn->prepare("SELECT * FROM `brz_games` WHERE `id`=?");
  $checkIfGameExists->bind_param("i", $_POST['id']);
  $checkIfGameExists->execute();
  $gameCheckResults = $checkIfGameExists->get_result();

  if($_POST['id'] == null || $gameCheckResults->num_rows == 0){
    die("An error has occured. The game does not exist"); 
  }

  $game = $gameCheckResults->fetch_assoc();

  if(strlen($_POST['desc']) < 2 || strlen($_POST['desc'] > 200)){
    die("An error has occured. Description must be between 3-200 characters");
  }

  if (!isset($_FILES['img'])){
    $target_dir = $game["thumbnail"];
  }else{
    $ext1 = pathinfo($_FILES['img']['name']);

    $ext = $ext1['extension'];
    if($ext != "png"){
      die("An error has occured. Image must have the .png extension");
    }

    $size = filesize($_FILES['img']['tmp_name']);

    if($size > 3145728){
      die("An error has occured. Image must not exceed 3 megabytes");
    }

    list($width, $height) = getimagesize($_FILES['img']['tmp_name']);
    if(($width != 800) && ($height != 480)){
      die("An error has occured. Your thumbnail must be 800x480 pixels");
    }

    $target_dir = "/assets/images/games/".rand(0,9999999).".png";
    move_uploaded_file($_FILES['img']['tmp_name'], "..".$target_dir."");
  }

  $stmt2 = $conn->prepare("UPDATE `brz_games` SET `description`=?, `thumbnail`=? WHERE `id`=?") or die(mysqli_error($conn));
  $stmt2->bind_param("ssi", $_POST['desc'], $target_dir, $_POST['id']) or die(mysqli_error($conn));
  $stmt2->execute() or die(mysqli_error($conn));

  die($_POST['id']);

}

if(isset($_POST['id']) && isset($_POST['color']) && isset($_POST['sky']) && isset($_POST['status'])){
  $checkIfGameExists = $conn->prepare("SELECT * FROM `brz_games` WHERE `id`=?");
  $checkIfGameExists->bind_param("i", $_POST['id']);
  $checkIfGameExists->execute();
  $gameCheckResults = $checkIfGameExists->get_result();

  if($_POST['id'] == null || $gameCheckResults->num_rows == 0){
    die("An error has occured. The game does not exist"); 
  }

  $game = $gameCheckResults->fetch_assoc();

  if(check_hex_color($_POST['color']) == false){
    die("An error has occured. You have entered an invalid hex color code");
  }

  if(checkRemoteFile($_POST['sky']) == false){
    die("An error has occured. You have entered an invalid skybox image");
  }
  
  if(!in_array($_POST['status'], ["public","private"])){
    die("An error has occured. You have entered an invalid game visibility");
  }
  
  $stmt2 = $conn->prepare("UPDATE `brz_games` SET `baseplate_color`=?, `skybox`=?, `status`=? WHERE `id`=?") or die(mysqli_error($conn));
  $stmt2->bind_param("sssi", $_POST['color'], $_POST['sky'], $_POST['status'], $_POST['id']) or die(mysqli_error($conn));
  $stmt2->execute() or die(mysqli_error($conn));

  die($_POST['id']);
}
?>