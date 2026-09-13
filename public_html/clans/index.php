<?php
$pageName = "Clans";
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');

$getClans = $conn->prepare("SELECT * FROM `brz_clans` WHERE `approved`=? ORDER BY `members` DESC");
$getClans->bind_param("i", $i);
$getClans->execute();
$clanResult = $getClans->get_result();
?>
<div class="container">
	<div class="content">
		<div style="margin:auto;" class="column is-8">
			<button class="button is-success is-pulled-right" onclick="window.location='/clans/create'">Create</button>
			<p class="title has-text-white">Clans</p>
			<input id="search" onkeydown="alert('Coming soon')" class="input is-dark" placeholder="Search for a clan">
			<div style="height:20px;"></div>
			<div class="columns is-multiline">
				<?php foreach ($clanResult as $clan) { 
					$owner = userInfo($clan['owner']);
					?>
					<div class="column is-4">
						<div class="box">
							<img width="256" height="256" class="image is-responsive is-centered" src="<?=$clan['icon']?>">
							<a href="/clans/view/<?=$clan['id']?>">
								<p class="subtitle has-text-white truncate is-centered"><?=htmlentities($clan['name'])?></p></a>
								<p class="has-text-white is-centered">Owned by: 
									<a href="/profile/<?=$owner['username']?>"><?=$owner['username']?></a></p>
									<p class="has-text-white is-centered">Members: <?=$clan['members']?></p>
								</div>
							</div>
							<?php	}	?>
						</div>
					</div>
				</div>
			</div>
			<?=footer()?>