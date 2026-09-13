<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
include($_SERVER['DOCUMENT_ROOT'] . '/shop/itemBackend.php');
if(!isset($_GET['id']) || empty($_GET['id']) || mysqli_num_rows(GetItem($_GET['id'])) == null){
  header("Location: /error/code/404");
}
$item = GetItem($_GET['id']);
$it = $item->fetch_assoc();
?>
<div class="box">
  <h2 class="title has-text-white">Recommended Items</h2>
  <div class="columns">
    <?php
    $notWantedStatus = "offsale";
    $getItems = $conn->prepare("SELECT * FROM `items_brz` WHERE `type`=? AND `pending`=0 AND `deleted`=0 AND `status`!=? ORDER BY RAND() LIMIT 4") or die($conn->error);
    $getItems->bind_param("ss", $it['type'], $notWantedStatus) or die($conn->error);
    $getItems->execute() or die($conn->error);
    $itemResults = $getItems->get_result();
    foreach($itemResults as $item){
      $poster = userInfo($item['creator']);
    ?>
    <div class="column is-3">
      <div class="box is-centered">
        <a href="/shop/item/<?=$item['id']?>">
          <img src="<?=$item['headshot']?>">
        </a><br>
        <a class="has-text-weight-bold truncate" href="/shop/item/<?=$item['id']?>"><?=$item['item_name']?></a>
        <text class="has-text-white truncate">By: <a href="/profile/<?=$poster['id']?>"><?=$poster['username']?></a></text>
      </div>
    </div>
    <?php } ?>
  </div>
</div>