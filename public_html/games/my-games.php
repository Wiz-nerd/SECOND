<?php
$pageName = "My Games";
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');

if(!isset($_SESSION['id']) && $user['membership'] == 0){
 die(header("Location: /error/code/404")); 
}

$getGames = $conn->prepare("SELECT `id`,`creator`,`thumbnail`,`title` FROM `brz_games` WHERE `creator`=? ORDER BY `visits` DESC");
$getGames->bind_param("i", $user['id']);
$getGames->execute();
$gameResult = $getGames->get_result();

?>
<div class="container">
  <div class="content">
    <div style="margin:auto;" class="column is-9">
      <button onclick="window.location='/games/create'" class="button is-success is-pulled-right">Create</button>
      <p class="title has-text-white">My Games</p>
      <div class="columns is-multiline">
        <?php
  foreach ($gameResult as $game) {
    $creator = userInfo($game['creator']);
        ?>
        <div class="column is-4">
          <div class="box is-centered">
            <a href="/games/view/<?=$game['id']?>">
              <img width="200" height="50" src="<?=$game['thumbnail']?>">
              <div style="height:10px;"></div>
              <text class="subtitle has-text-white"><?=$game['title']?></text></a><br>
            <a href="/profile/<?=$creator['username']?>">
              <text class="has-text-white">By: <?=$creator['username']?></text></a>
          </div>
        </div>
        <?php } ?>
      </div>
    </div>
  </div>
</div>