<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');

if(isset($_GET['id']) && isset($_GET['rank'])){
	$getClan = $conn->prepare("SELECT * FROM `brz_clans` WHERE `id`=? AND `approved`=?");
	$getClan->bind_param("ii", $_GET['id'], $i);
	$getClan->execute();
	$clanResult = $getClan->get_result();

	if($clanResult->num_rows == 0){
		print("<p class='has-text-white'>This clan does not exist</p>");
		die();
	}

	$getRanks = $conn->prepare("SELECT * FROM `brz_clan_ranks` WHERE `id`=? AND `clan_id`=?");
	$getRanks->bind_param("ii", $_GET['rank'], $_GET['id']);
	$getRanks->execute();
	$rankResult = $getRanks->get_result();

	if($rankResult->num_rows == 0){
		print("<p class='has-text-white'>This rank does not exist</p>");
		die();
	}

	$getMembers = $conn->prepare("SELECT * FROM `brz_clan_members` WHERE `clan_id`=? AND `rank_id`=?");
	$getMembers->bind_param("ii", $_GET['id'], $_GET['rank']);
	$getMembers->execute();
	$memberResult = $getMembers->get_result();
}
?>
<div class="columns is-multiline">
	<?php foreach($memberResult as $member){ 
		$members = userInfo($member['user_id']);
		?>
		<div class="column is-2">
			<a href="/profile/<?=$members['username']?>">
				<img class="image is-centered" src="<?=$members['avatar_img']?>"></img>
				<p class="is-centered has-text-white truncate"><?=$members['username']?></p>
			</a>
		</div>
			<?php } ?>
	</div>