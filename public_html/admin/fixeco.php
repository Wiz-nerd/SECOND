<?php
include("{$_SERVER['DOCUMENT_ROOT']}/config/param.php");
include("{$_SERVER['DOCUMENT_ROOT']}/config/header.php");
if($user['id'] != (3 || 2)){
  die("Access Denied");
}

if(isset($_POST['id'])){
  $get = $conn->query("SELECT * FROM `fixeco`");
  while(($i = $get->fetch_assoc())){
    $newCurr = $i["curr"] / 1.2;
    $c = round($newCurr);
    if($i["curr"] != 0){
    	$conn->query("UPDATE fixeco SET curr='$c' WHERE id='$i[id]'");
    }
  }
  echo"success";
}
?>
<div class="section">
  <div class="container">
    <div class="content">
      <h1 class="title has-text-white">Fix Economy (DO NOT USE UNLESS EMERGENCY)</h1>
      <p class="subtitle has-text-white">The moment you press this button, all of the regular hats, faces, and tools will have their prices increased by 1.2x. Along side that, all of the Brickorzo users will have their currency cut by 1.2x aswell. DO NOT USE THIS FEATURE UNLESS YOUR ECONOMY IS BROKEN.</p>
      <form method="post">
        <input name="id" class="input is-dark mb-3" placeholder="Item ID">
        <button type="submit" class="button is-danger">Fix Serials</button>
      </form>
    </div>
  </div>
</div>