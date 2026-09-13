<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(!isset($_GET['query']) || $_GET['query'] == null){
	die("Invalid Request");
}

if(userInfoNumU($_GET['query'])->num_rows == 0){
	die("User does not exist");
}

$player = userInfo2($_GET['query']);

switch($user['rank']){
	case 1:
	$controls = [
	'Ban User' => 'ban',
	'Reset Username' => 'reset'
	];
	break;
	case 2:
	$controls = [
	'Ban User' => 'ban',
	'Reset Username' => 'reset'
	];
	break;
	case 3:
	$controls = [
	'Ban User' => 'ban',
	'Reset Username' => 'reset'
	];
	break;
	case 4:
	$controls = [
	'Ban User' => 'ban',
	'Reset Username' => 'reset'
	];
	break;
	case 5:
	$controls = [
	'Ban User' => 'ban',
	'Reset Username' => 'reset',
	'Manage Diamonds' => 'eco',
	'IP Ban User' => 'ip'
	];
	break;
}

$getBans = $conn->prepare("SELECT * FROM `bans` WHERE `user`=? ORDER BY `id` DESC LIMIT 15");
$getBans->bind_param("i", $player['id']);
$getBans->execute();
$banResult = $getBans->get_result();

if(empty($player['email'])){
	$email = "None";
}else{
	$email = htmlentities($player['email']);
}

$getAlts = $conn->prepare("SELECT * FROM `brz_users` WHERE `ip`=? ORDER BY `id` DESC");
$getAlts->bind_param("s", $player['ip']);
$getAlts->execute();
$altResult = $getAlts->get_result();
?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma-extensions@6.2.7/dist/css/bulma-extensions.min.css" integrity="sha256-RuPsE2zPsNWVhhvpOcFlMaZ1JrOYp2uxbFmOLBYtidc=" crossorigin="anonymous">
<input type="hidden" id="uid" value="<?=$player['id']?>">
<div class="columns">
	<div class="column is-3">
		<div class="box">
			<center>
				<img class="is-centered" src="<?=$player['avatar_img']?>">
			</center>
		</div>
		<div class="box">
			<?php foreach($controls as $control => $activate){ ?>
			<button value="<?=$activate?>" id="<?=$activate?>" class="button is-danger is-fullwidth"><?=$control?></button>
			<div style="height:10px;"></div>
			<?php } ?>
		</div>
	</div>
	<div class="column is-7">
		<div class="box">
			<h3 class="subtitle has-text-white">Information</h3>
			<b class="has-text-white">ID: </b><text class="has-text-white"><?=$player['id']?></text><br>
			<b class="has-text-white">Username: </b><text class="has-text-white"><?=$player['username']?></text><br>
			<b class="has-text-white">Bucks: </b><text class="has-text-white"><?=$player['shards']?></text><br>
			<b class="has-text-white">Diamonds: </b><text class="has-text-white"><?=$player['diamonds']?></text><br>
			<b class="has-text-white">Join Date: </b><text class="has-text-white"><?=date("d/m/Y", $player['join_date'])?></text><br>
			<b class="has-text-white">Last Online: </b><text class="has-text-white"><?=date("d/m/Y", $player['last_online'])?></text>
		</div>
		<div class="box">
			<div class="columns">
				<div class="column">
					<h3 class="subtitle has-text-white">Ban History</h3>
					<?php 
					if($banResult->num_rows != 0){
						foreach($banResult as $ban){ 
							switch($ban['ban_length']){
								case 3600:
								$length = "1 hour";
								break;
								case 43200:
								$length = "12 hour";
								break;
								case 86400:
								$length = "1 day";
								break;
								case 259200:
								$length = "3 days";
								break;
								case 604800:
								$length = "7 days";
								break;
								case 1209600:
								$length = "14 days";
								break;
								case 262800288:
								$length = "1 month";
								break;
								default:
								$length = "forever";
							}
							?>
							<text class="has-text-white">Banned on <?=date("d/m/Y", $ban['date_of_ban'])?> for <?=$length?></text>
							<?php } 
						}else{
							print("<p class='has-text-white'>This user has never been banned</p>");
						}?>
					</div>
					
			</div>
		</div>
	</div>
	<script>
	$("#reset").click(function(){
		$.post("/admin/posts/moderation", {
			user: $("#uid").val(),
			reset: $("#reset").val()
		}).done(function(resp){
			if(resp != "succ"){
				$("#error").text(resp)
			}else{
				$("#succ").text("Successfully resetted username");
			}
		}).fail(function(){
			alert("There was an error with this request");
		})
	})

	$("#ban").click(function(){
		var uid = <?=$player['id']?>;
		window.location='/admin/ban/'+uid+''
	})
	</script>