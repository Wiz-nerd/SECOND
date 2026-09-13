<?php
/*include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(!isset($_SESSION['id'])){
 die("You are not authenticated"); 
}
$check = $conn->query("SELECT * FROM `brz_inv` WHERE `item`='84' AND `buyer`='$user[id]'");
if($check->num_rows == 1){
  header("Location: /shop/item/84");
}else{
$id = 84;
$giveCorruption = $conn->prepare("INSERT INTO `brz_inv` VALUES(NULL,?,?,?)") or die(mysqli_error($conn));
$giveCorruption->bind_param("iii", $id, $user['id'], $o) or die(mysqli_error($conn));
$giveCorruption->execute() or die(mysqli_error($conn));
header("Location: /shop/item/$id");
}*/
?>