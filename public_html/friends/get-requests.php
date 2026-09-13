<?php
$stmt = $conn->prepare("SELECT * FROM `friends` WHERE `status`=? AND `reciever`=?");
$stmt->bind_param("ii", $o, $user['id']);
$stmt->execute();
$result = $stmt->get_result();
if(mysqli_num_rows($result) != 0){
echo'<p></p><div class="columns is-multiline">';
foreach($result as $friends){
$sender = userInfo($friends['sender']);
?>
<div class="column is-3">
<a href="/profile/<?=$sender['username']?>"><img width="100" src="<?=$sender['avatar_img']?>" class="image is-centered"></a>
<a href="/profile/<?=$sender['username']?>"><text class="has-text-white"><?=$sender['username']?></text></a><br>
<button id="accept" value="<?=$friends['id']?>" class="button is-success is-small">Accept</button>
<button id="decline" value="<?=$friends['id']?>" class="button is-danger is-small">Decline</button>
</div>
<?php
}
echo'</div>';
}else{
	echo"<p class='has-text-white'>You have no incoming friend requests</p>";
}
?>