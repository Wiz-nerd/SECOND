<?php
header("Content-Type: application/json");
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
$array = array();
$array["data"] = array();
if(isset($_GET['sort']) && isset($_GET['page'])){
  $sort = mysqli_real_escape_string($conn, $_GET['sort']);
  $page = mysqli_real_escape_string($conn, $_GET['page']);
  $offset = ($page - 1) * 12;
  if($offset < 0) { $offset = 0; }
  $limited = "limited";
  if($sort!='all'){
  	$getItems = $conn->prepare("SELECT * FROM `items_brz` WHERE `type`=? AND `status`=? ORDER BY `id` DESC LIMIT 12 OFFSET ?") or die($conn->error);
  	$getItems->bind_param("sss", $sort, $limited, $offset) or die($conn->error);
  }else{
    $getItems = $conn->prepare("SELECT * FROM `items_brz` WHERE `status`=? ORDER BY `id` DESC LIMIT 12 OFFSET ?") or die($conn->error);
  	$getItems->bind_param("ss", $limited, $offset) or die($conn->error);
  }
  $getItems->execute() or die($conn->error);
  $item = $getItems->get_result();
  $iR = $conn->query("SELECT * FROM `items_brz` WHERE `type`='$_GET[sort]' AND `deleted`=0 AND `pending`=0 AND `status`='limited'") or die(mysqli_error($conn));
  $pages = ceil($iR->num_rows / 12);
  if (mysqli_num_rows($item) != 0){
    while ($u = $item->fetch_assoc()){

      $info = [
        "response" => "OK",
        "id" => $u['id'],
        "name" => $u['item_name'],
        "description" => $u['item_body'],
        "price" => $u['item_price'],
        "creator" => $u['creator'],
        "headshot" => $site[5] . $u['headshot'],
        "stock" => $u['stock'],
        "stock_left" => $u['stock_left'],
        "time" => $u['time'],
        "status" => $u['status'],
        "type" => $u['type'],
        "deleted" => $u['deleted'],
        "pending" => $u['pending'],
        "page" => $page,
        "pages" => $pages
      ];
      array_push($array["data"], $info);
    }
    echo json_encode($array);

  }else{
    $info = ["response" => "No results found"];
    array_push($array["data"], $info);
    echo json_encode($array);
  }

}else{
  $info = ["response" => "Invalid request"];
  array_push($array["data"], $info);
  echo json_encode($array);
}
?>