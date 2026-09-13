<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
include($_SERVER['DOCUMENT_ROOT'] . '/shop/itemBackend.php');
if(!isset($_GET['id']) || empty($_GET['id']) || mysqli_num_rows(GetItem($_GET['id'])) == null){
  header("Location: /error/code/404");
}
$item = GetItem($_GET['id']);
$it = $item->fetch_assoc();
if($it['status'] != "limited"){
  die('<h2 class="title has-text-white">You cannot resell regular items</h2>'); 
}

$getUserInventory = $conn->prepare("SELECT * FROM `brz_inv` WHERE `item`=? AND `buyer`=?");
$getUserInventory->bind_param("ii", $_GET['id'], $user['id']);
$getUserInventory->execute();
$inventoryResults = $getUserInventory->get_result();
?>
<div class="box">
  <text class="title has-text-white">Reselling</text>
  <button class="button is-success is-pulled-right" id="sell">Sell Item</button>
  <p id="commentMsg" class="help is-danger"></p>
  <hr>
  <?php
  $getItemComments = $conn->prepare("SELECT * FROM `item_selling_brz` WHERE `item_id`=? ORDER BY `price` ASC");
  $getItemComments->bind_param("i", $it['id']);
  $getItemComments->execute();
  $commentResults = $getItemComments->get_result();
  foreach($commentResults as $comments){
    $poster = userInfo($comments['seller']);
  ?>
  <div class="columns">
    <div class="column is-2 column-is-vcentered">
      <img style="width:65px;" src="<?=$poster['avatar_img']?>">
    </div>
    <div class="column is-8">
      <div class="is-size-6">
        <text class="has-text-white" style="cursor:initial;">Serial #<?=$comments['serial']?> from <?=$poster['username']?></text><br>
        <button id="rebuy" name="<?=$comments['id']?>" value="<?=$comments['serial']?>" class="button is-success is-small mt-2">Buy for <?=$comments['price']?> Shards</button>
      </div>
    </div>
  </div>
  <?php
    }  
  ?>
</div>
<script>
  $("button[id=rebuy]").click(function(){
    $.post("/shop/pages/rebuy", {
      id: this.name,
      serial: $(this).attr("value")
    }).done(function(resp){
      if(resp != "success"){
        $("#commentMsg").text(resp)
      }else{
        location.reload()
      }
    }).fail(function(){
      alert("Something has went wrong in the request")
    })
  })

  $("#sell").click(function(){
    const modal = $(".modal");
    modal.addClass("is-active")
  })

  function closeModal(){
    const modal = $(".modal");
    modal.removeClass("is-active")
  }
  
  $("#submit").click(function(){
    $.post("/shop/pages/rebuy", {
     item: $("#item").val(),
     price: $("#price").val()
    }).done(function(resp){
      if(resp != "success"){
       $("#err").text(resp) 
      }else{
       window.location.reload()
      }
    }).fail(function(){
      alert("Something went wrong in the request")
    })
  })
</script>
<div class="modal">
  <div onclick="closeModal()" class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title has-text-white">Sell Item</p><button class="delete" onclick="closeModal()" aria-label="close"></button></header>
    <section class="modal-card-body">
      <p class="has-text-danger has-text-weight-bold" id="err"></p><label class="label has-text-white">Select Item</label>
      <div class="select is-dark is-fullwidth mb-3">
        <select id="item">
          <?php foreach($inventoryResults as $inventory): ?>
          <option value="<?=$inventory['id']?>">Serial #<?=$inventory['serial']?></option>
          <?php endforeach; ?>
        </select>
      </div>
      <label class="label has-text-white">Price</label>
      <input type="number" class="input is-dark" id="price">
      <div style="height:20px"></div>
    </section>
    <footer class="modal-card-foot"><button id="submit" class="button is-centered is-black">Sell Item</button></footer>
  </div>
</div>