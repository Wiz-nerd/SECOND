<?php
$conn = mysqli_connect('localhost','id21046293_bdb','Toddlogan08!','id21046293_bdb');
if(isset($_POST['request'])){
  switch($_POST['request']){
    case "accept":
      $e = $conn->query("SELECT * FROM `items_brz` WHERE `id`='$_POST[id]'");
      $item = $e->fetch_assoc();

      if($item['pending'] == 0){
        die("e1"); 
      }

      if($item['deleted'] == 1){
        die("e2"); 
      }

      $stmt2 = $conn->prepare("INSERT INTO `brz_inv` VALUES(NULL,?,?,?)");
      $serial = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `item`='$item[id]'")) + 1;
      $stmt2->bind_param("iii", $item['id'], $item['creator'], $serial);
      $stmt2->execute();
      $stmt = $conn->prepare("UPDATE `items_brz` SET `pending`=0 WHERE `id`=?");
      $stmt->bind_param("i", $item['id']);
      $stmt->execute();
      die($conn->insert_id);
      break;
    case "decline":
      $e = $conn->query("SELECT * FROM `items_brz` WHERE `id`='$_POST[id]'");
      $item = $e->fetch_assoc();

      if($item['pending'] == 0){
        die("e1"); 
      }

      if($item['deleted'] == 1){
        die("e2"); 
      }

      $stmt = $conn->prepare("UPDATE `items_brz` SET `pending`=0, `deleted`=1 WHERE `id`=?");
      $stmt->bind_param("i", $item['id']);
      $stmt->execute();
      die($conn->insert_id);
      break;
  }
}