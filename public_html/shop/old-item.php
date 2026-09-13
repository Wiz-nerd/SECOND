<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
include($_SERVER['DOCUMENT_ROOT'] . '/shop/itemBackend.php');
if(!isset($_GET['id']) || empty($_GET['id']) || mysqli_num_rows(GetItem($_GET['id'])) == null){
  header("Location: /error/code/404");
}
$item = GetItem($_GET['id']);
$it = $item->fetch_assoc();
$pageName = htmlentities($it['item_name']);
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
$creator = userInfo($it['creator']);
switch($it['status']){
  case 'onsale':
    $price = "$it[item_price] Shards";
    break;
  case 'offsale':
    $price = "Offsale";
    break;
  case 'free':
    $price = "Free";
    break;
  case 'limited':
    $price = "$it[item_price] Shards";
    break;
  default:
    $price = "???";
}
?>
<div class="container">
  <center>
    <div class="column is-8-desktop is-four-fifths-tablet is-centered">
      <nav class="breadcrumb is-marginless" aria-label="breadcrumbs">
        <ul>
          <li class="has-text-white"><a class="has-text-white" href="/shop/">Store</a></li>
          <li class="has-text-white is-active"><a class="has-text-white" href="#" aria-current="page"><?=htmlentities($it['item_name'])?></a></li>
        </ul>
      </nav>
      <div class="push-10"></div>
      <div class="box">
        <div class="columns">
          <div class="column is-6">
            <div class="has-text-white is-size-5"><?=htmlentities($it['item_name'])?></div>
            <img height="250" width="250" src='<?=$it['headshot']?>'>
            <p class="has-text-danger"><b><?=$it['stock_left']?>/<?=$it['stock']?> left</b></p>
            <p class="has-text-white"><b>Description</b>:</p>
            <p class="has-text-white"><?=htmlentities($it['item_body'])?></p>
          </div>
          <div class="column is-6">
            <div class="columns">
              <div class="column is-6">
              </div>
            </div>
            <a href="/profile/<?=$creator['username']?>"><img width="50" src="<?=$creator['avatar_img']?>" /></a><br /><br />
            <p class="has-text-white"><b>Creator</b>: <a class="has-text-white" href="/profile/<?=$creator['username']?>"><?=$creator['username']?></a></p>
            <p class="has-text-white"><b>Price</b>: <?=$price?></p>
            <p class="has-text-white"><b>Created</b>: <?=date("M d Y h:i:s A", $it['time'])?></p>
            <p class="has-text-white"><b>Sales</b>: <?=getItemSales($it['id'])?></p><br>
            <form method='post' id='e'>
              <button id="buy" class="button is-success">Purchase Item</button>
              <p class="help is-danger" id="purchase_message"></p>
              <p class="help is-success" id="s"></p>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="column"></div>
    </div>
</div>
<script>
  $("#e").submit(function(e){
    e.preventDefault();
    $.post("/shop/itemBackend", {
      buy: <?=$it['id']?>
    }).done(function(resp){
      if(resp != "Successfully bought item!") {
        $("#purchase_message").text(resp);
      } else {
        setTimeout($('#buy').prop('disabled', true), 1000);
        $("#s").text(resp);
      };
    });
  });
</script>
<?php
  footer();
?>