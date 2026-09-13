<?php
include("{$_SERVER['DOCUMENT_ROOT']}/config/param.php");
include("{$_SERVER['DOCUMENT_ROOT']}/config/header.php");
if($user['id'] != (3 || 2)){
  die("Access Denied"); 
}

if(isset($_POST['id'])){
  $get = $conn->query("SELECT * FROM `brz_inv` WHERE `item`='$_POST[id]'");
  if(GetItem($_POST['id'])->num_rows == 0){
    echo"invalid id"; 
  }else{
    $number = $get->num_rows;
    $x = 0;
    while(($i = $get->fetch_assoc())){
      $x++;
      $conn->query("UPDATE brz_inv SET serial='$x' WHERE id='$i[id]'");
    }
    echo"success";
  }
}
?>
<div class="section">
  <div class="container">
    <div class="content">
      <h1 class="title has-text-white">Fix Serials from item</h1>
      <form method="post">
        <input name="id" class="input is-dark mb-3" placeholder="Item ID">
        <button type="submit" class="button is-danger">Fix Serials</button>
      </form>
    </div>
  </div>
</div>