<?php
include("{$_SERVER['DOCUMENT_ROOT']}/config/param.php");
if(isset($_POST['id']) && isset($_POST['serial'])){
  if(!isset($_SESSION['id'])){
    die("You are not logged in"); 
  }

  $checkIfListingExists = $conn->prepare("SELECT * FROM `item_selling_brz` WHERE `id`=? AND `serial`=?");
  $checkIfListingExists->bind_param("ii", $_POST['id'], $_POST['serial']);
  $checkIfListingExists->execute();
  $results = $checkIfListingExists->get_result();

  if($results->num_rows == 0){
    die("The listing does not exist");
  }

  $item = $results->fetch_assoc();

  if($user['shards'] < $item['price']){
    die("You cannot afford this item"); 
  }

  if($user['id'] == $item['seller']){
    die("You cannot buy your own item"); 
  }

  $checkIfListingExists = $conn->prepare("SELECT * FROM `brz_inv` WHERE `item`=? AND `serial`=?");
  $checkIfListingExists->bind_param("ii", $item['item_id'], $_POST['serial']);
  $checkIfListingExists->execute();
  $results = $checkIfListingExists->get_result();

  if($results->num_rows == 0){
    die("The item does not exist");
  }

  $newShards = $user['shards'] - $item['price'];
  $profits = round($item['price'] / 3) % 50;
  $currentProfits = $item['price'] - $profits;

  $updateCurrency = $conn->prepare("UPDATE `brz_users` SET `shards` = ? WHERE `id`=?");
  $updateCurrency->bind_param("ii", $newShards, $user['id']);
  $updateCurrency->execute();

  $updateSellerCurr = $conn->prepare("UPDATE `brz_users` SET `shards` = `shards` + ? WHERE `id`=?");
  $updateSellerCurr->bind_param("ii", $currentProfits, $item['seller']);
  $updateSellerCurr->execute();

  $swapOwners = $conn->prepare("UPDATE `brz_inv` SET `buyer`=? WHERE `item`=? AND `serial`=?");
  $swapOwners->bind_param("iii", $user['id'], $item['item_id'], $_POST['serial']);
  $swapOwners->execute();
  
  $addSaleLog = $conn->prepare("INSERT INTO `item_sales` VALUES(NULL,?,?)");
  $addSaleLog->bind_param("ii", $item['item_id'], $item['price']);
  $addSaleLog->execute();
  
  calculateRAP($item['item_id'], true);

  $deleteListing = $conn->prepare("DELETE FROM `item_selling_brz` WHERE `id`=? AND `serial`=?");
  $deleteListing->bind_param("ii", $_POST['id'], $_POST['serial']);
  $deleteListing->execute();
  die("success");
}

if(isset($_POST['item']) && isset($_POST['price'])){
  if(!isset($_SESSION['id'])){
    die("You are not logged in"); 
  }

  $checkIfItemExists = $conn->prepare("SELECT * FROM `brz_inv` WHERE `id`=?");
  $checkIfItemExists->bind_param("i", $_POST['item']);
  $checkIfItemExists->execute();
  $itemResults = $checkIfItemExists->get_result();

  if($itemResults->num_rows == 0){
    die("Item does not exist");
  }

  if(!is_numeric($_POST['price']) || $_POST['price'] < 1 || $_POST['price'] > 10000000){
    die("Price must be a number, and be between 1-10000000 Shards"); 
  }

  $item = $itemResults->fetch_assoc();

  $checkIfListingExists = $conn->prepare("SELECT * FROM `item_selling_brz` WHERE `item_id`=? AND `serial`=?");
  $checkIfListingExists->bind_param("ii", $item['item'], $item['serial']);
  $checkIfListingExists->execute();
  $results = $checkIfListingExists->get_result();

  if($results->num_rows > 0){
    die("Item is already put up for sale");
  }

  if($item['buyer'] != $user['id']){
    die("You cannot sell other users' limiteds");
  }

  $checkIfItemLimited = $conn->prepare("SELECT * FROM `items_brz` WHERE `id`=?");
  $checkIfItemLimited->bind_param("i", $item['item']);
  $checkIfItemLimited->execute();
  $itemResult = $checkIfItemLimited->get_result()->fetch_assoc();

  if($itemResult['status'] != "limited"){
    die("Item must be a limited");
  }

  $addListing = $conn->prepare("INSERT INTO `item_selling_brz` VALUES(NULL,?,?,?,?)");
  $addListing->bind_param("iiii", $item['item'], $item['serial'], $_POST['price'], $user['id']);
  $addListing->execute();
  die("success");
}
?>