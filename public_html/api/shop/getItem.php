<?php
header("Content-type: json");
include("{$_SERVER['DOCUMENT_ROOT']}/config/param.php");
if(isset($_GET['id'])){
  if(GetItem($_GET['id'])->num_rows == 0){
    $info = [
      "response" => "404"
    ];
    echo json_encode($info);
  }else{
    $u = GetItem($_GET['id'])->fetch_assoc();
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
        "rap" => $u['rap']
      ];
    echo json_encode($info);
  }
}
?>