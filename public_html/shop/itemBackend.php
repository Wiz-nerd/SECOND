<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');

function getItemSales($id){
  global $conn;
  $getSales = $conn->prepare("SELECT * FROM `brz_inv` WHERE `item`=?");
  $getSales->bind_param("i", $id);
  $getSales->execute();
  $result = $getSales->get_result();
  return mysqli_num_rows($result);
}

function GetItems($type){
  global $conn;
  $o = 0;
  $getItems = $conn->prepare("SELECT * FROM `items_brz` WHERE `type`=? AND `pending`=? AND `deleted`=? ORDER BY `id` DESC LIMIT 25") or die($conn->error);
  $getItems->bind_param("sii", $type, $o, $o) or die($conn->error);
  $getItems->execute() or die($conn->error);
  $itemResult = $getItems->get_result() or die($conn->error);
  return $itemResult;
}

if(isset($_POST['buy'])){
  if(!isset($_SESSION['id'])){
    die("You are not logged in");
  }
  $post = GetItem(mysqli_real_escape_string($conn, $_POST['buy']));
  $item = $post->fetch_assoc();
  if($item['pending'] != 0){
    die("Item is still pending review");
  }
  if($item['deleted'] == 1){
    die("Item is deleted");
  }

  switch($item['status']){
    case 'offsale':
      die("Item is offsale");
      break;

    case 'free':
      $checkOwnership = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `item`='$item[id]' AND `buyer`='$_SESSION[id]'"));
      if(($checkOwnership > 0) && ($item['is_crate'] == "no")){
        die("You already own this item");
      }
      $stmt = $conn->prepare("INSERT INTO `brz_inv` VALUES(NULL,?,?,?)");
      $serial = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `item`='$item[id]'")) + 1;
      $stmt->bind_param("iii", $item['id'], $user['id'], $serial);
      $stmt->execute();
      die("Successfully bought item!");
      break;

    case 'onsale':
      $checkOwnership = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `item`='$item[id]' AND `buyer`='$_SESSION[id]'"));
      if(($checkOwnership > 0) && ($item['is_crate'] == "no")){
        die("You already own this item");
      }
      if($item['item_price'] > $user['shards']){
        die("You cannot afford this item");
      }
      if($user['id'] == $item['creator']){
        die("You cannot buy your own item");
      }
      $stmt = $conn->prepare("INSERT INTO `brz_inv` VALUES(NULL,?,?,?)");
      $serial = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `item`='$item[id]'")) + 1;
      $stmt->bind_param("iii", $item['id'], $user['id'], $serial);
      $stmt->execute();

      $newShards = $user['shards'] - $item['item_price'];
      $updateCurr = $conn->prepare("UPDATE `brz_users` SET `shards`=? WHERE `id`=?");
      $updateCurr->bind_param("ii", $newShards, $user['id']);
      $updateCurr->execute();

      $profits = round($item['item_price'] / 3) % 50;
      $currentProfits = $item['item_price'] - $profits;
      $updateCurr = $conn->prepare("UPDATE `brz_users` SET `shards` = `shards` + ? WHERE `id`=?");
      $updateCurr->bind_param("ii", $currentProfits, $item['creator']);
      $updateCurr->execute();
      die("Successfully bought item!");
      break;

    case 'limited':
      $checkOwnership = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `item`='$item[id]' AND `buyer`='$_SESSION[id]'"));
      if($checkOwnership > 0){
        die("You already own this item");
      }
      if($item['item_price'] > $user['shards']){
        die("You cannot afford this item");
      }
      if($user['id'] == $item['creator']){
        die("You cannot buy your own item");
      }
      if($item['stock_left'] == 0){
        die("This item is out of stock");
      }
      $stmt = $conn->prepare("INSERT INTO `brz_inv` VALUES(NULL,?,?,?)");
      $serial = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `item`='$item[id]'")) + 1;
      $stmt->bind_param("iii", $item['id'], $user['id'], $serial);
      $stmt->execute();

      $newShards = $user['shards'] - $item['item_price'];
      $updateCurr = $conn->prepare("UPDATE `brz_users` SET `shards`=? WHERE `id`=?");
      $updateCurr->bind_param("ii", $newShards, $user['id']);
      $updateCurr->execute();

      $profits = round($item['item_price'] / 3) % 50;
      $currentProfits = $item['item_price'] - $profits;
      $updateCurr = $conn->prepare("UPDATE `brz_users` SET `shards` = `shards` + ? WHERE `id`=?");
      $updateCurr->bind_param("ii", $currentProfits, $item['creator']);
      $updateCurr->execute();

      $updateCurr = $conn->prepare("UPDATE `items_brz` SET `stock_left` = `stock_left` - ? WHERE `id`=?");
      $updateCurr->bind_param("ii", $i, $item['id']);
      $updateCurr->execute();
      die("Successfully bought item!");
      break;
  }
}

if(isset($_POST['comment']) && isset($_POST['item'])){
  $comment = $_POST['comment'];
  $item = $_POST['item'];

  if(!isset($_SESSION['id'])){
    die("You are not logged in");
  }

  if(strlen($comment) < 3 || strlen($comment) > 300){
    die("Your comment must be between 3-300 characters"); 
  }

  if(GetItem($item)->num_rows == 0){
    die("This item doesn't exist"); 
  }


  if($user['flood'] > time()){
    die("You are posting threads too fast");
  }

  $insertComment = $conn->prepare("INSERT INTO `item_comments` VALUES(NULL,?,?,?,?)");
  $insertComment->bind_param("isii", $user['id'], $comment, $item, $time);
  $insertComment->execute();
  $stmt = $conn->prepare("UPDATE `brz_users` SET `flood`=? WHERE `id`=?");
  $stmt->bind_param("ii", $newFlood, $user['id']);
  $stmt->execute();
  die("succ");
}

if(isset($_POST['crate']) && isset($_POST['serial'])){
  $getItem = $conn->prepare("SELECT * FROM `brz_inv` WHERE `id`=? AND `item`=? AND `buyer`=?");
  $getItem->bind_param("iii", $_POST['serial'], $_POST['crate'], $user['id']);
  $getItem->execute();
  $itemRes = $getItem->get_result();

  if($itemRes->num_rows == 0){
    die("An error occured. You do not own this crate.");
  }

  $getCrateItems = $conn->prepare("SELECT * FROM `brz_crates` WHERE `item_id`=?");
  $getCrateItems->bind_param("i", $_POST['crate']);
  $getCrateItems->execute();
  $crateResults = $getCrateItems->get_result();
  $crateVal = $crateResults->fetch_assoc();

  $array = [
    $crateVal['common'] => 50, 
    $crateVal['uncommon'] => 30, 
    $crateVal['rare'] => 15, 
    $crateVal['ultra'] => 4, 
    $crateVal['legendary'] => 0.9,
    $crateVal['mythical'] => 0.1
  ];

  $winningItem = determineItem($array);

  $stmt = $conn->prepare("INSERT INTO `brz_inv` VALUES(NULL,?,?,?)");
  $serial = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `item`='$_POST[crate]'")) + 1;
  $stmt->bind_param("iii", $winningItem, $user['id'], $serial);
  $stmt->execute();
  
  $deleteCrate = $conn->prepare("DELETE FROM `brz_inv` WHERE `id`=?");
  $deleteCrate->bind_param("i", $_POST['serial']);
  $deleteCrate->execute();
  die("$winningItem");
}
?>