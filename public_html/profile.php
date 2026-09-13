<?php
include($_SERVER['DOCUMENT_ROOT'] . '/profileBackend.php');
$pageName = "".htmlentities($profile['username'])."'s Profile";
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
?>
<meta name="keywords" content="brickorzo, brickorzo game, brickorzo thread, brickorzo user, brickorzo item">
<meta name="author" content="Brickorzo">
<meta name="publisher" content="Brickorzo">
<meta name="theme-color" content="#fe8447">
<meta name="description" content="<?=htmlentities($profile['bio'])?>">
<meta property="og:image" content="<?=$site[5] . $profile['avatar_img']?>">
<meta name="page-topic" content="Video Games">
<section style="margin:auto;" class="section">
<div class="container">
<div class="columns">
<div class="column is-3">
<div class="has-text-white is-size-5"><span style="color:<?=$color?>;">●</span> <?=$profile['username']?> 
<?php
$query = "SELECT COUNT(*) AS count FROM brz_users WHERE sub = 1";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);
$count = $row['count'];

if ($profile['sub'] == 1) {
    echo '<img title="Premium Subscription" style="cursor:default;margin-bottom:-4px;" width="100" alt="Premium" src="../assets/images/premium.png" />';
} else if ($profile['sub'] == 2) {
    echo '<img title="Deluxe Subscription" style="cursor:default;margin-bottom:-4px;" width="100" alt="Deluxe" src="../assets/images/deluxe.png" />';
}
?>
<?php
$getBans = $conn->prepare("SELECT * FROM `bans` WHERE `user`=? AND `expired`=?");
$getBans->bind_param("ii", $profile['id'], $o);
$getBans->execute();
$banResult = $getBans->get_result();
if($banResult->num_rows != 0){
 echo '<span style="color:red;font-size:23px;"> (Banned)</span>';
}
?>
<?php
$getPastUsernames = $conn->prepare("SELECT * FROM `past_names` WHERE `user_id`=?") or die($conn->error);
$getPastUsernames->bind_param("i", $profile['id']) or die($conn->error);
$getPastUsernames->execute() or die($conn->error);
$pastUsernameResults = $getPastUsernames->get_result() or die($conn->error);
if($pastUsernameResults->num_rows != 0){
?>
<a class="tooltip has-tooltip-multiline" data-tooltip="Past usernames: <?php foreach($pastUsernameResults as $pastUsernames){ echo $pastUsernames["username"] . ". "; } ?>"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
<?php
}else{}
?></div>
<div class="has-text-white is-size-9"><q><?=filter(bbcode($profile['status']))?></q></div>
<div class="box has-text-centered">
<div>
<img src="<?=$profile['avatar_img']?>">
</div><div style="height:10px"></div>
<p class="has-text-white"><?=nl2br(filter(bbcode($profile['bio'])))?></p>
<?php if(isset($_SESSION['id']) && $_SESSION['id'] != $profile['id']){ ?>
<br />
<div class="has-text-centered">
<?php if($friend == 1){
?>
<form method="post" id="friend" style="display:initial;">
<button id="f" class="button is-success is-small">Friend</button>
<?php
} else if($friend == 2){
?>
<form method="post" id="unfriend" style="display:initial;">
<button id="u" class='button is-danger is-small' id='send_friend'>Unfriend</button>
<?php
} else if($friend == 3){
?>
<button class='button is-small' disabled="true">Sent</button>
<?php
}
?>
</form>
<button onclick="window.location='/messages/send/<?=$profile['id']?>'" class="button is-black is-small">Message</button>
<button onclick="window.location='/trades/send?id=<?=$profile['id']?>'" class="button is-info is-small">Trade</button>
</div>
<?php } ?>
</div>
<div class="has-text-white is-size-5" style="width:100%;">Brickorzo Stats</div>
<div class="has-text-white box" style="width:100%;">
<p><b>Join date</b>: <span class="is-pulled-right"><?=date("m/d/Y", $profile['join_date'])?></span></p>
<p><b>Forum posts</b>: <span class="is-pulled-right"><?=$profile['posts']?></span></p>
<p><b>Profile views</b>: <span class="is-pulled-right"><?=$profile['profile_views']?></span></p>
<p><b>UserID</b>: <span class="is-pulled-right"><?=$profile['id']?></span></p>
</div>
</div>
<div class="column is-7">
<div class="columns is-mobile is-marginless-bottom is-paddingless-bottom">
<div class="column is-6 is-paddingless-bottom">
<div class="has-text-white is-size-5">Friends</div>
</div>
<div class="column is-6 is-paddingless-bottom">
</div>
</div>
<div class="box">
<div class="columns is-mobile is-multiline has-text-centered">
<?php
$getFriends = $conn->prepare("SELECT * FROM friends WHERE (sender=? OR reciever=?) AND status=1 ORDER BY id DESC LIMIT 0,6");
$getFriends->bind_param("ii", $profile['id'], $profile['id']);
$getFriends->execute();
$friends = $getFriends->get_result();

if($friends->num_rows == 0){
  echo '<p class="has-text-white">This user has no friends!</p>';
} else {

while($friende = $friends->fetch_assoc()){
if($friende['sender'] == $profile['id']){

  $friendInfo = userInfo($friende['reciever']);

} else if($friende['reciever'] == $profile['id']){

  $friendInfo = userInfo($friende['sender']);
}

if($friendInfo['last_online'] + 180 > time()){
$color = "green";
}else{
$color = '#A9A9A9';
}
?>
<div class="column is-3">
<a href="/profile/<?=$friendInfo['username']?>"><img width="50" src="<?=$friendInfo['avatar_img']?>" /></a>
<p class="text-truncate">
<span style="color:<?=$color?>;">●</span>
<a class="has-text-white" href="/profile/<?=$friendInfo['username']?>"><?=$friendInfo['username']?></a></p>
</div>
<?php }} ?>
</div>
</div>
<div class="has-text-white is-size-5 mb-3">Badges (<a class="has-text-white" href="/badges/">View all</a>)</div>
<div class="box">
<div class="columns is-multiline has-text-centered">
<?php
$getBadges = $conn->prepare("SELECT * FROM `badges` WHERE `user_id`=? ORDER BY `id` DESC");
$getBadges->bind_param("i", $profile['id']);
$getBadges->execute();
$results = $getBadges->get_result();
foreach($results as $userBadges){
$getBadges2 = $conn->prepare("SELECT * FROM `brz_badges` WHERE `id`=? ORDER BY `id` DESC");
$getBadges2->bind_param("i", $userBadges['badge_id']);
$getBadges2->execute();
$results2 = $getBadges2->get_result();
$b = $results2->fetch_assoc();
?>
<div class="column is-3">
<img width="90" height="90" class="tooltip image is-centered" src="<?=$b['icon']?>"></img>
<text class="tooltip has-text-white has-tooltip-multiline" data-tooltip="<?=$b['desc']?>"><?=$b['name']?></text>
</div>
<?php } ?>
</div>
</div>
<div class="has-text-white is-size-5 mb-3">Inventory</div>
<div class="box">
  <div class="columns is-multiline">
    <div class="column is-2">
      <aside class="menu">
        <ul class="menu-list">
          <li><a style="background-color:#fe8447;color:white;border-radius:0px;" onclick="getInventory('hat')">Hats</a></li>
          <li><a style="background-color:#fe8447;color:white;border-radius:0px;" onclick="getInventory('face')">Faces</a></li>
          <li><a style="background-color:#fe8447;color:white;border-radius:0px;" onclick="getInventory('tool')">Tools</a></li>
          <li><a style="background-color:#fe8447;color:white;border-radius:0px;" onclick="getInventory('shirt')">Shirts</a></li>
          <li><a style="background-color:#fe8447;color:white;border-radius:0px;" onclick="getInventory('pant')">Pants</a></li>
        </ul>
      </aside>
    </div>
    <div class="column is-8">
    <div id="inventory">
      <div id="items" class="columns is-multiline">

      </div>
      </div>
    </div>
  </div>
</div>
</div>
</div>
</div>
</section>
<script>
  $(function(){
    getInventory("hat")
  })

  function getInventory(cat){
    $.getJSON( "/api/user/getInventory", { 
      sort: cat, 
      user: <?=$profile['id']?> 
    }).done(function( json ) {
      $("#items").html('')
      $.each(json.data, function( i, item ) {
        if(item.response != "There are no items in this category"){
          $("#items").append('<div class="column is-4"><div class="box"> <div class="has-text-centered"><a href="/shop/item/'+item.id+'"><img class="image is-centered" width="122" height="122" src="'+item.headshot+'"></a><div><text class="truncate" title="'+item.name+'"><a class="has-text-white title is-6 truncate" href="/shop/item/'+item.id+'">'+item.name+'</a></text></div></div></div></div>')
        }else{
          $("#items").append('<p class=\'has-text-white mt-2\'>There are no items in this category</p>')
        }
      });
    }).fail(function(jqxhr, textStatus, error){
      var err = textStatus + ", " + error;
      console.log( "Request Failed: " + err );
    }); 
  }

  $("#friend").submit(function(e){
    e.preventDefault();
    $.post("/profileBackend", {
      user: <?=$profile['id']?>
    }).done(function(resp){
      if(resp != "success") {
        alert(resp);
      } else {
        setTimeout($("#f").addClass('is-loading'), 1000);
        $("#f").removeClass('is-loading');
        $("#f").removeClass('is-success');
        $('#f').prop('disabled', true);
        $("#f").text("Sent");
      };
    });
  });
  $("#unfriend").submit(function(e){
    e.preventDefault();
    $.post("/profileBackend", {
      unfriend: <?=$profile['id']?>
    }).done(function(resp){
      if(resp != "success") {
        alert(resp);
      } else {
        setTimeout($("#u").addClass('is-loading'), 1000);
        $("#u").removeClass('is-loading');
        $("#u").removeClass('is-danger');
        $("#u").addClass('is-success');
        $('#u').prop('disabled', true);
        $("#u").text("Friend");
      };
    });
  });
</script>
<?=footer()?>