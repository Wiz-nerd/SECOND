<?php
$pageName = "Games";

$neededStatus = "public";
$getGames = $conn->prepare("SELECT `id`,`creator`,`thumbnail`,`title` FROM `brz_games` WHERE `status`=? ORDER BY `visits` DESC");
$getGames->bind_param("s", $neededStatus);
$getGames->execute();
$gameResult = $getGames->get_result();

#$newPass = password_hash('lord7302', PASSWORD_DEFAULT);

#echo($newPass);

?>
<div class="container">
  <div class="content">
    <div style="margin:auto;" class="column is-9">
      <button onclick="window.location='/games/my-games'" class="button is-danger is-pulled-right ml-2">My Games</button>
      <button onclick="window.location='/games/create'" class="button is-success is-pulled-right">Create</button>
      <p class="title has-text-white">Games</p>
      <div></div>
      <p class="subtitle has-text-weight-light has-text-white">Thanks for buying a subscription! Games are currently singleplayer only, go make a game to begin.</p>
      <div></div>
      <div class="columns is-multiline">
        <?php
  foreach ($gameResult as $game) {
    $creator = userInfo($game['creator']);
    $getPlayerCount = $conn->prepare("SELECT * FROM `brz_game_joins` WHERE `game_id`=?");
    $getPlayerCount->bind_param("i", $game['id']);
    $getPlayerCount->execute();
    $playerCount = $getPlayerCount->get_result()->num_rows;

        ?>
        <div class="column is-4">
          <div class="box is-centered">
            <a href="/games/view/<?=$game['id']?>">
              <img width="200" height="50" src="<?=$game['thumbnail']?>">
              <div style="height:10px;"></div>
              <text class="subtitle has-text-white"><?=$game['title']?></text></a><br>
            <a href="/profile/<?=$creator['username']?>">
              <text class="has-text-white">By: <?=$creator['username']?></text></a><br>
            <text class="has-text-danger has-text-weight-bold"><?=$playerCount?> Playing</text>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>