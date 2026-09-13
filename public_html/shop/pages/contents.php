<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
include($_SERVER['DOCUMENT_ROOT'] . '/shop/itemBackend.php');
if(!isset($_GET['id']) || empty($_GET['id']) || mysqli_num_rows(GetItem($_GET['id'])) == null){
  header("Location: /error/code/404");
}
$item = GetItem($_GET['id']);
$it = $item->fetch_assoc();
if($it['is_crate'] != "yes"){
  die('<h2 class="title has-text-white">You cannot see anything inside of regular items</h2>'); 
}

?>
<div class="box">
  <text class="title has-text-white">Crate Contents</text>
  <hr>
  <?php
  $getItemComments = $conn->prepare("SELECT * FROM `brz_crates` WHERE `item_id`=?");
  $getItemComments->bind_param("i", $it['id']);
  $getItemComments->execute();
  $commentResults = $getItemComments->get_result();
  foreach($commentResults as $comments){
    $items = [
      GetItem($comments['common'])->fetch_assoc(),
      GetItem($comments['uncommon'])->fetch_assoc(),
      GetItem($comments['rare'])->fetch_assoc(),
      GetItem($comments['ultra'])->fetch_assoc(),
      GetItem($comments['legendary'])->fetch_assoc(),
      GetItem($comments['mythical'])->fetch_assoc(),
    ];
  ?>
  <div class="columns">
    <div class="column is-2 column-is-vcentered">
      <a href="/shop/item/<?=$items[0]['id']?>"><img src="<?=$items[0]['headshot']?>"></a>
    </div>
    <div class="column is-8">
      <div class="is-size-6">
        <text class="has-text-white" style="cursor:initial;"><?=$items[0]['item_name']?></text><br>
        <text class="has-text-white" style="cursor:initial;">Common Item</text><br>
      </div>
    </div>
  </div>
  <div class="columns">
    <div class="column is-2 column-is-vcentered">
      <a href="/shop/item/<?=$items[1]['id']?>"><img src="<?=$items[1]['headshot']?>"></a>
    </div>
    <div class="column is-8">
      <div class="is-size-6">
        <text class="has-text-white" style="cursor:initial;"><?=$items[1]['item_name']?></text><br>
        <text class="has-text-white" style="cursor:initial;">Uncommon Item</text><br>
      </div>
    </div>
  </div>
  <div class="columns">
    <div class="column is-2 column-is-vcentered">
      <a href="/shop/item/<?=$items[2]['id']?>"><img src="<?=$items[2]['headshot']?>"></a>
    </div>
    <div class="column is-8">
      <div class="is-size-6">
        <text class="has-text-white" style="cursor:initial;"><?=$items[2]['item_name']?></text><br>
        <text class="has-text-white" style="cursor:initial;">Rare Item</text><br>
      </div>
    </div>
  </div>
  <div class="columns">
    <div class="column is-2 column-is-vcentered">
      <a href="/shop/item/<?=$items[3]['id']?>"><img src="<?=$items[3]['headshot']?>"></a>
    </div>
    <div class="column is-8">
      <div class="is-size-6">
        <text class="has-text-white" style="cursor:initial;"><?=$items[3]['item_name']?></text><br>
        <text class="has-text-white" style="cursor:initial;">Ultra Item</text><br>
      </div>
    </div>
  </div>
  <div class="columns">
    <div class="column is-2 column-is-vcentered">
      <a href="/shop/item/<?=$items[4]['id']?>"><img src="<?=$items[4]['headshot']?>"></a>
    </div>
    <div class="column is-8">
      <div class="is-size-6">
        <text class="has-text-white" style="cursor:initial;"><?=$items[4]['item_name']?></text><br>
        <text class="has-text-white" style="cursor:initial;">Legendary Item</text><br>
      </div>
    </div>
  </div>
  <div class="columns">
    <div class="column is-2 column-is-vcentered">
      <a href="/shop/item/<?=$items[5]['id']?>"><img src="<?=$items[5]['headshot']?>"></a>
    </div>
    <div class="column is-8">
      <div class="is-size-6">
        <text class="has-text-white" style="cursor:initial;"><?=$items[5]['item_name']?></text><br>
        <text class="has-text-white" style="cursor:initial;">Mythical Item</text><br>
      </div>
    </div>
  </div>  <?php
    }  
  ?>
</div>