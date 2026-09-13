<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$errors = array();
$success = array();
if(!isset($_SESSION['id'])){
  die(header("Location: /login/"));
}

if(isset($_GET['id'])){

  $id = mysqli_real_escape_string($conn,$_GET['id']);

  $getTRD = $conn->prepare("SELECT * FROM `brz_trades_brz_orzo_brz` WHERE `id`=?");
  $getTRD->bind_param("i", $id);
  $getTRD->execute();
  $tradeQ = $getTRD->get_result();
  $trade = $tradeQ->fetch_assoc();

  if($trade['reciever']!=$user['id']){
    die(header("Location: /trades/"));
  }

  $getUSR = $conn->prepare("SELECT * FROM `brz_users` WHERE `id`=?");
  $getUSR->bind_param("i", $trade['sender']);
  $getUSR->execute();
  $usr = $getUSR->get_result();
  $u = $usr->fetch_assoc();

  $pageName = "Trade from $u[username]";
  include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');

  $giving = explode(',',$trade['giving']); #ik its called giving but this is what the user is getting
  $getting = explode(',',$trade['getting']); #and this is what the user is giving
}
?>

<div class='section'>
  <div class='container'>
    <?php foreach($errors as $error){?>
    <div class="notification is-danger">
      <p class="has-text-white"><?=$error?></p>
    </div>
    <?php } ?>
    <?php foreach($success as $succes){?>
    <div class="notification is-success">
      <p class="has-text-white"><?=$succes?></p>
    </div>
    <?php } ?>
    <?php if($trade['status'] == "p"): ?>
    <form method="post">
      <button name="d" class='button is-danger is-pulled-right ml-2'>Decline</button>
      <button name="a" class='button is-success is-pulled-right'>Accept</button>
    </form>
    <?php endif; ?>
    <p class="title has-text-white">Trade from <?=$u['username']?></p>
    <div class="columns">
      <div class="column is-6">
        <div class="box">
          <h2 class="subtitle has-text-white">You are giving</h2>
          <div class="columns is-multiline">
            <?php
  foreach($getting as $invid):
      $inv = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `id` = '$invid'"));
      $item = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `items_brz` WHERE `id` = '$inv[item]'"));
            ?>
            <div class="column is-4">
              <div class="box">
                <img src='<?=$item['headshot']?>' class='avatar'>
                <p title="Serial #<?=$inv['serial']?>" class="has-text-white has-text-centered truncate"><?=htmlentities($item['item_name'])?> #<?=$inv['serial']?></p>
                  <p class="has-text-white has-text-centered truncate">RAP: <?=calculateRAP($item['id'], false)?></p>
              </div>
            </div>
            <?php
  endforeach;
            ?>
          </div>
        </div>
      </div>
      <div class="column is-6">
        <div class="box">
          <h2 class="subtitle has-text-white">You are getting</h2>
          <div class="columns is-multiline">
            <?php
            foreach($giving as $invid):
            $inv = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `id` = '$invid'"));
            $item = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `items_brz` WHERE `id` = '$inv[item]'"));
            ?>
            <div class="column is-4">
              <div class="box">
                <img src='<?=$item['headshot']?>' class='avatar'>
                <p title="Serial #<?=$inv['serial']?>" class="has-text-white has-text-centered truncate"><?=htmlentities($item['item_name'])?> #<?=$inv['serial']?></p>
                <p class="has-text-white has-text-centered truncate">RAP: <?=calculateRAP($item['id'], false)?></p>
              </div>
            </div>
            <?php
  endforeach;
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<?php
if(isset($_POST['d'])){
  $tradeID = mysqli_real_escape_string($conn,$_POST['d']);
  $trade = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `brz_trades_brz_orzo_brz` WHERE `id` = '$id'"));

  if($user['id']!=$trade['reciever']){
    $errors[] = "An expected error occured";
  }else{

    if($trade['status'] == "d"){
      $errors[] = "The trade was already declined";
    }else{


      mysqli_query($conn,"UPDATE `brz_trades_brz_orzo_brz` SET `status` = 'd' WHERE `id` = '$trade[id]'");

      $success[] = "Successfully declined trade!";
      echo"<script>window.location='/trades/trade?id=$id'</script>";
    }
  }
}
if(isset($_POST['a'])){
  $tradeID = mysqli_real_escape_string($conn,$_POST['a']);
  $trade = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `brz_trades_brz_orzo_brz` WHERE `id` = '$id'"));

  if($user['id']!=$trade['reciever']){
    $errors[] = "An unexpected error occured";
  }

  if($trade['status'] == "a"){
    $errors[] = "The trade was already accepted";
  }

  $giving = explode(',',$trade['giving']); #ik its called giving but this is what the user is getting
  $getting = explode(',',$trade['getting']); #and this is what the user is giving

  #loop through GIVING and check if they own the items B)
  # no sneaky
  foreach($giving as $invid){
    $item = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `id` = '$invid'"));
    if($item['buyer']!=$trade['sender']){
      $errors[] = "The sender has one or more items missing from their inventory";
    }
  }

  foreach($getting as $invid){
    $item = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `id` = '$invid'"));
    if($item['buyer']!=$user['id']){
      $errors[] = "One or more items are missing from your inventory";
    }
  }

  #okay NOW transfer
  foreach($giving as $invid){
    mysqli_query($conn,"UPDATE `brz_inv` SET `buyer` = '$user[id]' WHERE `id` = '$invid'");
  }

  foreach($getting as $invid){
    mysqli_query($conn,"UPDATE `brz_inv` SET `buyer` = '$trade[sender]' WHERE `id` = '$invid'");
  }

  mysqli_query($conn,"UPDATE `brz_trades_brz_orzo_brz` SET `status` = 'a' WHERE `id` = '$trade[id]'");

  $success[] = "Successfully accepted trade";
  echo"<script>window.location='/trades/trade?id=$id'</script>";
}
footer();
?>
<script>
  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
</script>