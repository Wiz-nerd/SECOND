<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
$getUsers = $conn->prepare("SELECT * FROM `brz_users` ORDER BY `id` DESC LIMIT 5");
$getUsers->execute();
$userResult = $getUsers->get_result();
foreach($userResult as $users){
?>
<div class="columns">
<div class="column is-3">
<a href="/profile/<?=$users['username']?>"><img src="<?=$users['avatar_img']?>"></img></a>
</div>
<div class="column is-9">
<a href="/profile/<?=$users['username']?>"><text class="has-text-white"><?=$users['username']?></text></a><br>
<text class="has-text-white">Joined <?=date("m/d/Y", $users['join_date'])?></text>
</div>
</div>
<?php } ?>