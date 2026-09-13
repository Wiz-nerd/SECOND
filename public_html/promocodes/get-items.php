<?php
$stmt = $conn->prepare("SELECT * FROM `brz_promo` WHERE `active`=? ORDER BY `id` DESC LIMIT 5");
$stmt->bind_param("i", $i);
$stmt->execute();
$results = $stmt->get_result();
?>

<div class="columns">
<?php
foreach($results as $items){
$item3 = GetItem($items['item']);
$item = $item3->fetch_assoc();
?>
<div class="column is-3">
<div class="box">
<a href="/shop/item/<?=$item['id']?>">
<img src="<?=$item['headshot']?>">
<div style="height:5px"></div>
<p class="has-text-white truncate is-centered"><?=$item['item_name']?></p>
</a>
</div>
</div>
<?php } ?>
</div>