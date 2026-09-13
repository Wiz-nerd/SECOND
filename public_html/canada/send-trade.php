<?php
include("{$_SERVER['DOCUMENT_ROOT']}/config/param.php");

if(!isset($_SESSION['id'])){
  die(header("Location: /login/")); 
}

if(isset($_POST['give1']) && isset($_POST['give2']) && isset($_POST['give3']) && isset($_POST['get1']) && isset($_POST['get2']) && isset($_POST['get3']) && isset($_POST['user'])){

  $giving = [$_POST['give1'], $_POST['give2'], $_POST['give3']];
  $requesting = [$_POST['get1'], $_POST['get2'], $_POST['get3']];
  if(userInfoNumI($_POST['user'])->num_rows == 0){
    die("User does not exist"); 
  }

  foreach($giving as $give){
    $checkIfItemExists = $conn->prepare("SELECT * FROM `brz_inv` WHERE `item`=? AND `buyer`=? LIMIT 1");
    $checkIfItemExists->bind_param("ii", $give, $user['id']) or die(mysqli_error($conn));
    $checkIfItemExists->execute();
    $itemResult = $checkIfItemExists->get_result();
    if($itemResult->num_rows == null){
      die("One or more items that you are giving aren't on your inventory");
    }
  }

}
?>