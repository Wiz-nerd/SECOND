<?php
include('../config/param.php');

$checkIfGameExists = $conn->prepare("SELECT * FROM `brz_games` WHERE `id`=?");
$checkIfGameExists->bind_param("i", $_GET['id']);
$checkIfGameExists->execute();
$gameCheckResults = $checkIfGameExists->get_result();

if(!isset($_GET['id']) || $_GET['id'] == null || $gameCheckResults->num_rows == 0){
  die(header("Location: /error/code/404")); 
}

$game = $gameCheckResults->fetch_assoc();
$creator = userInfo($game['creator']);

$getPlayerCount = $conn->prepare("SELECT * FROM `brz_game_joins` WHERE `game_id`=?");
$getPlayerCount->bind_param("i", $game['id']);
$getPlayerCount->execute();
$playerCount = $getPlayerCount->get_result()->num_rows;

$pageName = filter(htmlentities($game['title']));
include('../config/header.php');

if(($game['status'] == "private") && ($user['id'] != $creator['id'])){
 die(header("Location: /error/code/404")); 
}

if($game['status'] == "deleted"){
 die(header("Location: /error/code/404")); 
}

if($user['id'] == $creator['id']){
 $gear = '<i onclick="window.location="/games/edit/'.$game['id'].'" class="fa fa-gear"></i>';
}else{
 $gear;
}

?>
<section class="section">
  <div class="container">
    <div class="content">
      <text class="title has-text-white"><?=filter(htmlentities($game['title']))?> <?php if($user['id'] == $creator['id']):?><i onclick="window.location='/games/edit/<?=$game['id']?>'" class="fas fa-cog"></i><?php endif; ?></text><br><div style="height:5px"></div>
      <text class="subtitle has-text-white">Created by <a href="/profile/<?=$creator['username']?>"><?=$creator['username']?></a></text>
      <br><br>
      <div class="columns">
        <div class="column is-8">
          <div class="box">
            <div class="columns">
              <div class="column is-6">
                <img width="100%" height="100%" src="<?=$game['thumbnail']?>" class="image">
                <div style="height:10px;"></div>
                <button id="play" class="button is-success is-fullwidth is-large">Play</button>
              </div>
              <div class="column is-5">
                <text class="has-text-white"><?=filter(nl2br(htmlentities($game['description'])))?></text>
              </div>
            </div>
            <hr>
            <div class="columns is-mobile">
              <div class="column is-4 is-centered">
                <text class="subtitle has-text-white has-text-weight-bold"><?=$playerCount?></text>
                <p class="has-text-white">Playing</p>
              </div>
              <div class="column is-4 is-centered">
                <text class="subtitle has-text-white has-text-weight-bold"><?=$game['visits']?></text>
                <p class="has-text-white">Visits</p>
              </div>
              <div class="column is-4 is-centered">
                <text class="subtitle has-text-white has-text-weight-bold"><?=date("d/m/Y", $game['time'])?></text>
                <p class="has-text-white">Creation Date</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?=footer()?>
<script>
  $("#play").click(function(){
    $.post("/games/test/gameAuthenticator", {
      gameId: <?=$game['id']?>
    }).done(function(resp){
      switch(resp){
        case "error1":
        setTimeout(function(){
          $("#err").text("You are already inside a game in an other tab.");
        }, 3000);
          break;
        case "error2":
        setTimeout(function(){
          $("#err").text("The game you are trying to play doesn't exist.");
        }, 3000);
          break;
        case "error3":
        setTimeout(function(){
          $("#err").text("You are not logged in.");
        }, 3000);
          break;
        case "error4":
        setTimeout(function(){
          $("#err").text("You cannot play Brickorzo games on your mobile device at the moment.");
        }, 3000);
          break;
        default:
        setTimeout(function(){
          //put ur site url here xd
          window.location='http://brikorzo.fun/'+resp+''
        }, 3000);
      }
    }).fail(function(){
      setTimeout(function(){
        $("#err").text("There was an internal error launching the game. We are sorry for the inconvenience.");
      }, 3000);
    }).always(function(){
      $(".section").append('<div class="modal is-active"><div onclick="closeModal()" class="modal-background"></div><div class="modal-card"><header class="modal-card-head"><p class="modal-card-title has-text-white">Launching Game</p><button class="delete" onclick="closeModal()" aria-label="close"></button></header><section class="modal-card-body"><center><img style="display:inline-block;" src="/assets/images/logo.png" class="image is-centered is-vcentered" width="150" height="150"><img style="display:inline-block;" src="/assets/images/loading.gif" class="image is-centered is-vcentered" width="150" height="150"></center><p class="has-text-danger has-text-weight-bold is-centered" id="err"></p><p class="subtitle has-text-white is-centered">The game client is currently loading. Please be patient.</p></section><footer class="modal-card-foot"></footer></div></div>');
    })
  })
</script>