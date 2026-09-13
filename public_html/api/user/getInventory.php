<?php
header("Content-Type: application/json");
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
$array = array();
$array["data"] = array();
if(isset($_GET['sort']) && isset($_GET['user'])) {
  $userInfo = mysqli_real_escape_string($conn, $_GET['user']);
  $type = mysqli_real_escape_string($conn, $_GET['sort']);
  $user2 = userInfo($_GET['user']);
  $itemsResult = $conn->query("SELECT `item` FROM `brz_inv` WHERE `buyer`='$user2[id]' ORDER BY `id` DESC");
  if(mysqli_num_rows($itemsResult) != 0){
    $invItems = array();
    while($row=$itemsResult->fetch_assoc()){
      $invItems[] = $row['item'];
    }
    if($type!='all'){
    $shopItems = $conn->query("SELECT * FROM `items_brz` WHERE `id` IN (".implode(',',array_map('intval',$invItems)).") AND `type`='$type' AND `deleted`=0 ORDER BY `id` DESC");
    }else{
    $shopItems = $conn->query("SELECT * FROM `items_brz` WHERE `id` IN (".implode(',',array_map('intval',$invItems)).") AND `deleted`=0 ORDER BY `id` DESC");
    }
    $items = isset($shopItems->num_rows);
    $r = 0;
    $count = 1;
    if(mysqli_num_rows($shopItems) != 0){
      while($itemRow=$shopItems->fetch_assoc()){
        $r++;
        $info = [
          "reponse" => "OK",
          "id" => $itemRow['id'],
          "name" => $itemRow['item_name'],
          "description" => $itemRow['item_body'],
          "price" => $itemRow['item_price'],
          "creator" => $itemRow['creator'],
          "headshot" => $site[5] . $itemRow['headshot'],
          "stock" => $itemRow['stock'],
          "stock_left" => $itemRow['stock_left'],
          "time" => $itemRow['time'],
          "status" => $itemRow['status'],
          "type" => $itemRow['type'],
          "deleted" => $itemRow['deleted'],
          "pending" => $itemRow['pending'],
          "rap" => $itemRow['rap']
        ];
        array_push($array["data"], $info);
      }
        echo json_encode($array);
        $count++;
      if ($count%4 != 1) {}
    }else{ 
      $info = ["response" => "There are no items in this category"];
      array_push($array["data"], $info);
      echo json_encode($array);
    }
  }else{ 
    $info = ["response" => "There are no items in this category"];
    array_push($array["data"], $info);
    echo json_encode($array);
  }
} else {
  $info = ["response" => "Invalid request"];
  array_push($array["data"], $info);
  echo json_encode($array);
}
?>