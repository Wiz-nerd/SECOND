<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$getUserCount = $conn->prepare("SELECT * FROM `brz_users`");
$getUserCount->execute();
$userResult = $getUserCount->get_result();
$getItemCount = $conn->prepare("SELECT * FROM `items_brz`");
$getItemCount->execute();
$ItemResult = $getItemCount->get_result();
$getClanCount = $conn->prepare("SELECT * FROM `brz_clans`");
$getClanCount->execute();
$ClanResult = $getClanCount->get_result();
$getGameCount = $conn->prepare("SELECT * FROM `brz_games`");
$getGameCount->execute();
$GameResult = $getGameCount->get_result();
?>
<div class="columns">
<div class="column is-2 is-centered">
<h2 class="title has-text-white"><?=$userResult->num_rows?></h2>
<p class="subtitle has-text-white">Users</p>
</div>
<div class="column is-2 is-centered">
<h2 class="title has-text-white"><?=$ItemResult->num_rows?></h2>
<p class="subtitle has-text-white">Items</p>
</div>
<div class="column is-2 is-centered">
<h2 class="title has-text-white"><?=$ClanResult->num_rows?></h2>
<p class="subtitle has-text-white">Clans</p>
</div>
<div class="column is-2 is-centered">
<h2 class="title has-text-white"><?=$GameResult->num_rows?></h2>
<p class="subtitle has-text-white">Games</p>
</div>
<div class="column is-2 is-centered">
<h2 class="title has-text-white"><?=phpversion()?></h2>
<p class="subtitle has-text-white">PHP Version</p>
</div>
</div>