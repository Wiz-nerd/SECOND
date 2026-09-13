<?php
header("Content-type: json");
include("{$_SERVER['DOCUMENT_ROOT']}/config/param.php");
$array = array();
$array["data"] = array();
if(isset($_GET['id'])){
  if(GetItemOwners($_GET['id'])->num_rows == 0){
    $info = [
      "response" => "404"
    ];
    echo json_encode($info);
  }else{
    foreach(GetItemOwners($_GET['id']) as $u){
      $info = [
        "response" => "OK",
        "id" => $u['id'],
        "user" => $u['buyer'],
        "serial" => $u['serial']
      ];
      array_push($array["data"], $info);
   	}
    echo json_encode($array);
  }
}
?>