<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$recieverN = userInfoNumI($_GET['id']);
if(!isset($_SESSION['id'])){
  die(header("Location: /login/")); 
}

if(!isset($_GET['id']) || $_GET['id'] == null || $recieverN->num_rows == 0){
  die(header("Location: /error/code/404")); 
}

$reciever = userInfo($_GET['id']);
if($reciever['id'] == $user['id']){
  die(header("Location: /error/code/404")); 
}

$getUserInventory = $conn->prepare("SELECT * FROM `brz_inv` WHERE `buyer`=? ORDER BY `id` DESC");
$getUserInventory->bind_param("i", $user['id']);
$getUserInventory->execute();
$user_crate = $getUserInventory->get_result();

$getRecieverInventory = $conn->prepare("SELECT * FROM `brz_inv` WHERE `buyer`=? ORDER BY `id` DESC");
$getRecieverInventory->bind_param("i", $reciever['id']);
$getRecieverInventory->execute();
$reciever_crate = $getRecieverInventory->get_result();

$pageName = "Send Trade to ".$reciever['username']."";
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
?>
<section class="section">
  <div class="container">
    <div class="content">
      <button id="send" class="button is-success is-pulled-right">Send</button>
      <input type="hidden" value="0" class="1">
      <input type="hidden" value="0" class="2">
      <input type="hidden" value="0" class="3">
      <input type="hidden" value="0" class="4">
      <input type="hidden" value="0" class="5">
      <input type="hidden" value="0" class="6">
      <h1 class="mb-5 title has-text-white">Send Trade to <?=$reciever['username']?></h1>
      <h3 id="err" class="mb-5 mt-1 subtitle has-text-danger"></h3>
      <div class="columns">
        <div class="column">
          <div class="box">
            <p class="subtitle has-text-white">Your Inventory (<a onclick="removeItems('sending')">Remove selected items</a>)</p>
            <div class="columns is-multiline">
              <?php foreach($user_crate as $utems){ 
  $item = GetItem($utems['item'])->fetch_assoc();
  if(isset($item)){
    if($item['status'] == "limited"){
              ?>
              <div class="column is-4" onclick="addLimited(<?=$utems['id']?>)">
                <div class="box" id="<?=$utems['id']?>">
                  <div class="has-text-centered">
                    <img class="image is-centered" width="122" height="122" src="<?=$item['headshot']?>">
                    <div>
                      <text class="truncate" title="<?=$item['item_name']?>">
                        <a class="has-text-white title is-6 truncate" href="/shop/item/<?=$item['id']?>"><?=$item['item_name']?></a></text>
                      <text class="has-text-white">
                        Serial #<?=$utems['serial']?>
                      </text>
                    </div>
                  </div>
                </div>
              </div>
              <?php }}} ?>
            </div>
          </div>
        </div>
        <div class="column">
          <div class="box">
            <p class="subtitle has-text-white">Their Inventory</p>
            <div class="columns is-multiline">
              <?php foreach($reciever_crate as $utems){ 
  $item = GetItem($utems['item'])->fetch_assoc();
  if(isset($item)){
    if($item['status'] == "limited"){
              ?>
              <div class="column is-4" onclick="requestLimited(<?=$utems['id']?>)">
                <div class="box" id="<?=$utems['id']?>">
                  <div class="has-text-centered">
                    <img class="image is-centered" width="122" height="122" src="<?=$item['headshot']?>">
                    <div>
                      <text class="truncate" title="<?=$item['item_name']?>">
                        <a class="has-text-white title is-6 truncate" href="/shop/item/<?=$item['id']?>"><?=$item['item_name']?></a></text>
                      <text class="has-text-white">
                        Serial #<?=$utems['serial']?>
                      </text>
                    </div>
                  </div>
                </div>
              </div>
              <?php } } } ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?=footer()?>
<script>
  let g1 = $(".1"), g2 = $(".2"), g3 = $(".3"), re1 = $(".4"), re2 = $(".5"), re3 = $(".6");

  function removeItems(type){
    if(type == "sending"){
      $("div[onclick=\"addLimited(" + g1.val() +")").prop("disabled", false)
      $("div[onclick=\"addLimited(" + g2.val() +")").prop("disabled", false)
      $("div[onclick=\"addLimited(" + g3.val() +")").prop("disabled", false)
      $("#" + g1.val()).removeClass("is-border-success");
      $("#" + g2.val()).removeClass("is-border-success");
      $("#" + g3.val()).removeClass("is-border-success");
      g1.val(0)
      g2.val(0)
      g3.val(0)
    }else{
      re1.val(0).removeClass("is-border-success");
      re2.val(0).removeClass("is-border-success");
      re3.val(0).removeClass("is-border-success");
    }
  }

  function addLimited(id) {
    if ($("#" + id).hasClass("is-border-success"))
      $("#" + id).removeClass("is-border-success");

    if (0 == g1.val()) (g1.val(id)), 0 != re1.val() && $("#send").attr("disabled", !1);
    else if (0 == g2.val()) g2.val(id);
    else {
      if (0 != g3.val()) return;
      g3.val(id);
    }
    $("#" + id).addClass("is-border-success");
    $("div[onclick=\"addLimited(" + id +")").prop("disabled", true)
  }

  function requestLimited(id) {
    if ($("#" + id).hasClass("is-border-success"))
      $("#" + id).removeClass("is-border-success");

    if (0 == re1.val()) (re1.val(id)), 0 != g1.val() && $("#send").attr("disabled", !1);
    else if (0 == re2.val()) re2.val(id);
    else {
      if (0 != re3.val()) return;
      re3.val(id);
    }
    $("#" + id).addClass("is-border-success");
  }

  $("#send").click(function(){
    $.post("/canada/send-trade.php", { 
      give1: g1.val(), 
      give2: g2.val(), 
      give3: g3.val(), 
      get1: re1.val(), 
      get2: re2.val(), 
      get3: re3.val(), 
      user: <?=$reciever['id']?>
    }).done(function(resp) {
      if(resp != "success"){
        $("#err").text(resp)
      }else{
        $("#send").attr("disabled", !0)
        $("#err").text("Successfully sent trade!")
      }
    }).fail(function(){
      $("#err").text("An unexpected error occured")
    })
  })
</script>