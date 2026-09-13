<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(!isset($_SESSION['id'])){
  die(header("Location: /login/"));
}

if(isset($_GET['id'])){

  $id = mysqli_real_escape_string($conn,$_GET['id']);
  $uQ = $conn->prepare("SELECT * FROM `brz_users` WHERE `id` = ?");
  $uQ->bind_param("i", $id);
  $uQ->execute();
  $res = $uQ->get_result();
  if(mysqli_num_rows($res)!=1){ die(header("Location: /error/code/404")); }
  $u = mysqli_fetch_array($res);

  $acc1_INV = mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `buyer` = '$user[id]'");
  $acc2_INV = mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `buyer` = '$id'");

  $INV1 = array();
  $INV2 = array();

  $INVIDS1 = array();
  $INVIDS2 = array();

  $pageName = "Trade with $u[username]";
  include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');

  while(($i=mysqli_fetch_array($acc1_INV))){
    $item = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `items_brz` WHERE `id` = '$i[item]'"));
    if($item['status']=='limited'){
      array_push($INV1,$i['item']);
      array_push($INVIDS1,$i['id']);
    }
  }

  while(($i=mysqli_fetch_array($acc2_INV))){
    $item = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `items_brz` WHERE `id` = '$i[item]'"));
    if($item['status']=='limited'){
      array_push($INV2,$i['item']);
      array_push($INVIDS2,$i['id']);
    }
  }
?>

<div class='section'>
  <div class='container'>
    <button onclick='submit()' class='button is-success is-pulled-right'>Send Trade</button>
    <p class="title has-text-white">Send trade to <?=$u['username']?></p>
    <div id='res'></div>
    <div class="columns">
      <div class="column is-6">
        <div class="box">
          <p class="subtitle has-text-white">Your Inventory</p>
          <div class="columns is-multiline">
            <?php
    $ID = 0;
  foreach($INV1 as $id):
  $item = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `items_brz` WHERE `id` = '$id'"));
  $inv = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `id` = '$INVIDS1[$ID]'"));
            ?>
            <div class='column is-4'>
              <center><img class='is-centered has-text-centered' src='<?=$item['headshot']?>'></center>
              <p class='has-text-white has-text-centered truncate'><?=$item['item_name']?> #<?=$inv['serial']?></p>
              <p class='has-text-white has-text-centered truncate'>RAP: <?=calculateRAP($item['id'], false)?></p>
              <button onclick='give(<?=$inv['id']?>)' id='<?=$inv['id']?>' class='button is-success is-fullwidth'>Add</button>
            </div>
            <?php
              $ID++;
  endforeach;
            ?>
          </div>
        </div>
      </div>
      <div class="column is-6">
        <div class="box">
          <p class="subtitle has-text-white"><?=$u['username']?>'s Inventory</p>
          <div class='columns is-multiline'>
            <?php
              $ID = 0;
  foreach($INV2 as $id):
  $item = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `items_brz` WHERE `id` = '$id'"));
  $inv = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM `brz_inv` WHERE `id` = '$INVIDS2[$ID]'"));
  echo"
      <div class='column is-4'>
        <center><img class='is-centered has-text-centered' src='$item[headshot]'></center>
        <p class='has-text-white has-text-centered truncate'>$item[item_name] #$inv[serial]</p>
        <p class='has-text-white has-text-centered truncate'>RAP: ".calculateRAP($item['id'], false)."</p>
        <button onclick='get($inv[id])' id='$inv[id]' class='button is-success is-fullwidth'>Add</button>
      </div>";
  $ID++;
  endforeach;
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script>
  const giving = [];
  const getting = [];

  function remove(a,id){
    for( var i = 0; i < a.length; i++){
      if ( a[i] === id){
        a.splice(i, 1);
      }
    }
  }

  function give(id){
    if(giving.indexOf(id) === -1){
      giving.push(id);
      document.getElementById(id).innerHTML = 'Remove';
      document.getElementById(id).classList.remove('is-success');
      document.getElementById(id).classList.add('is-danger');
    }else{
      remove(giving,id);
      document.getElementById(id).innerHTML = 'Add';
      document.getElementById(id).classList.remove('is-danger');
      document.getElementById(id).classList.add('is-success');
    }
  }

  function get(id){
    if(getting.indexOf(id) === -1){
      getting.push(id);
      document.getElementById(id).innerHTML = 'Remove';
      document.getElementById(id).classList.remove('is-success');
      document.getElementById(id).classList.add('is-danger');
    }else{
      remove(getting,id);
      document.getElementById(id).innerHTML = 'Add';
      document.getElementById(id).classList.remove('is-danger');
      document.getElementById(id).classList.add('is-success');
    }
  }

  function submit(){
    var url = '/trades/submit.php?give=';
    var gi = giving.join(',');
    var ge = getting.join(',');
    url += gi;
    url += '&get=';
    url += ge;
    url += '&u=<?=$u['id']?>';
    $('#res').load(url);
  }
</script>
<?php
footer();
}
?>