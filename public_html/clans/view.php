<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');

$getClan = $conn->prepare("SELECT * FROM `brz_clans` WHERE `id`=?");
$getClan->bind_param("i", $_GET['id']);
$getClan->execute();
$clanResult = $getClan->get_result();

if(!isset($_GET['id']) || $_GET['id'] == null || $clanResult->num_rows == 0){
	header("Location: /error/code/404");
}

$clan = $clanResult->fetch_assoc();
$pageName = htmlentities($clan['name']);

$getRanks = $conn->prepare("SELECT * FROM `brz_clan_ranks` WHERE `clan_id`=?");
$getRanks->bind_param("i", $_GET['id']);
$getRanks->execute();
$rankResult = $getRanks->get_result();

$getDRank = $conn->prepare("SELECT * FROM `brz_clan_ranks` WHERE `clan_id`=? AND `default`=?");
$getDRank->bind_param("ii", $_GET['id'], $i);
$getDRank->execute();
$rankDResult = $getDRank->get_result();
$defaultRank = $rankDResult->fetch_assoc();

include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');

$owner = userInfo($clan['owner']);

$getMember = $conn->prepare("SELECT * FROM `brz_clan_members` WHERE `clan_id`=? AND `user_id`=?");
$getMember->bind_param("ii", $clan['id'], $user['id']);
$getMember->execute();
$memberResult = $getMember->get_result();

if(isset($_SESSION['id'])){
if($memberResult->num_rows == 1){
	$button = '<input type="button" id="leave" value="Leave" class="button is-danger is-fullwidth"></input>';
}else{
	$button = '<input type="button" id="join" value="Join" class="button is-success is-fullwidth"></input>';
}
}else{
	$button = "";
}

if($user['id'] == $clan['owner']){
	$clog = '<a href="/clans/edit/'.$clan['id'].'"><i class="fa fa-cog" aria-hidden="true"></i></a>';
}else{
	$clog = '';
}
?>
<div class="container">
	<div class="content">
		<div class="box">
			<div class="columns">
				<div class="column is-3">
					<center>
						<img width="256" height="256" class="image" src="<?=$clan['icon']?>">
						<text style="font-weight:600;" class="has-text-white is-size-3 truncate"><?=htmlentities($clan['name'])?> <?=$clog?></text>
						<text id="count" class="subtitle has-text-white">Members: <?=$clan['members']?></text><br>
						<text class="subtitle has-text-white truncate">Owned by: <a href="/profile/<?=$owner['username']?>"><?=$owner['username']?></a></text><div style="height:1px;"></div>
					</center>
					<p class="help is-danger" id="error"></p>
					<?=$button?>
					<hr>
					<text style="font-weight:600;" class="has-text-white is-size-3 truncate">Clan News</text>
					<q class="has-text-white"><?=htmlentities($clan['announcement'])?></q>
				</div>
				<div class="column is-7">
					<p class="has-text-white"><?=nl2br(htmlentities($clan['bio']))?></p>
				</div>
			</div>
		</div>
		<div class="box">
			<div class="select is-dark is-pulled-right">
				<select id="ranks" onchange="getMembers()">
					<?php foreach($rankResult as $ranks){ ?>
					<option value="<?=$ranks['id']?>"><?=htmlentities($ranks['name'])?></option>
					<?php } ?>
				</select>
			</div>
			<p class="title has-text-white">Members</p>
			<div id="members"></div>
		</div>

	</div>
</div>
<?=footer()?>
<script>
$(function(){
	var clan = <?=$clan['id']?>;
	var rank = <?=$defaultRank['id']?>;
	$.get("/clans/get-members.php?id="+clan+"&rank="+rank+"", function(data, status){
		$("#members").html(data)
		console.log(status)
	})
})

function getMembers() {
	var clan = <?=$clan['id']?>;
	var rank = $("#ranks").val();
	$.get("/clans/get-members.php?id="+clan+"&rank="+rank+"", function(data, status){
		$("#members").html(data)
		console.log(status)
	})
}

$("#join").click(function(){
	$.post("/clans/join-clan", {
		clan: <?=$clan['id']?>
	}).done(function(resp){
		if(resp != "success"){
			$("#error").text(resp)
			setTimeout(function(){
				$("#error").empty()
			}, 3000);
		}else{
			$("#join").removeClass("is-success")
			$("#join").addClass("is-danger")
			$("#join").val("Leave")
		}
	}).fail(function(){
		alert("There was a problem performing the request")
	})
})

$("#leave").click(function(){
	$.post("/clans/leave-clan", {
		clan: <?=$clan['id']?>
	}).done(function(resp){
		if(resp != "success"){
			$("#error").text(resp)
			setTimeout(function(){
				$("#error").empty()
			}, 3000);
		}else{
			$("#leave").removeClass("is-danger")
			$("#leave").addClass("is-success")
			$("#leave").val("Join")
		}
	}).fail(function(){
		alert("There was a problem performing the request")
	})
})
</script>