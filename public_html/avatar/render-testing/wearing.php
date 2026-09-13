<?php
include("../../config/param.php");

$getAvatar = $conn->prepare("SELECT * FROM `brz_avatar` WHERE `user_id` = ?");
$getAvatar->bind_param("i", $user['id']);
$getAvatar->execute();
$avResult = $getAvatar->get_result();
if($avResult->num_rows == 0){
	$stmt = $conn->prepare("INSERT INTO `brz_avatar` (`user_id`,`hat`) VALUES(NULL, ?)");
    $stmt->bind_param("i", $o);
    $stmt->execute();
    die();
}
$userAvatar = (object) $avResult->fetch_assoc();

$itemArray = [
"hat" => $userAvatar->{'hat'},
"hat2" => $userAvatar->{'hat2'},
"hat3" => $userAvatar->{'hat3'},
"hat4" => $userAvatar->{'hat4'},
"hat5" => $userAvatar->{'hat5'},
"shirt" => $userAvatar->{'shirt'},
"tool" => $userAvatar->{'tool'},
"pant" => $userAvatar->{'pant'},
"face" => $userAvatar->{'face'}
];

echo"<div class='columns is-multiline'>";
foreach ($itemArray as $potato => $item) {
$itemID = $item;
$findItemSQL = GetItem($itemID);
if(mysqli_num_rows($findItemSQL) > 0) {
$itemRow = $findItemSQL->fetch_assoc();
$kek = $potato;
?>
<div class="column is-3">
<div class="box is-centered">
<a href="/shop/item/<?=$itemRow["id"]?>">
<img src="<?=$itemRow['headshot']?>"></img>
</a><br>
<a class="truncate" href="/shop/item/<?=$itemRow["id"]?>"><?=htmlentities($itemRow['item_name'])?></a>
<button onclick="remove('<?=$potato?>')" class="button is-danger is-fullwidth">Remove</button>
</div>
</div>
<?php
} 
}
?>
</div>