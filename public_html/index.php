<?php
$pageName = "Dashboard";
include_once($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(!isset($_SESSION['id'])){
include_once('landing.php');
die();
}
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
include($_SERVER['DOCUMENT_ROOT'] . '/home/functions.php');
?>
<section class="section">
<div class="container">
<script>console.log("Welcome to Brickorzo!")</script>
<?php foreach($errors as $error){
echo"<div class='notification is-danger has-text-white'>
$error
</div>";
}

foreach($succ as $success){
echo"<div class='notification is-success has-text-white'>
$success
</div>";
} ?>
<div class="columns">
<div class="column is-3">
<div class="box has-text-centered " id="profile_imagebox">
<div id="avatarContainer">
<img src="<?=$user['avatar_img']?>">
</div><br>
<button onclick="window.location='/avatar/'" class="button is-black">Avatar</button>
<button onclick="window.location='/settings/'" class="button is-black">Settings</button>
</div>
<div class="box">
<center>
<h2 class='title has-text-white'>Blog Posts</h2>
<hr>
<?=GetNews();?>
</center>
</div>
</div>
<div class="column is-7">
<div class="columns is-mobile is-marginless-bottom is-paddingless-bottom">
<div class="column is-6 is-paddingless-bottom">
</div>
<div class="column is-6 is-paddingless-bottom">
</div>
</div>
<div class="box">
<h4 style='display:flex;' class="subtitle has-text-white">Feed</h4>
<form method='post'>
<input name='status' value="<?=htmlentities($user['status'])?>" class='input is-dark'>
</form>
<hr>
<?php while($status = $Statuses->fetch_assoc()){
$post = userInfo($status['poster_id']);
?>
<div class="columns">
<div class="column is-2 column-is-vcentered">
<div class="avatar_forum">
<img class="avatar_forumimg" style="width:65px;" src="<?=$post['avatar_img']?>">
</div>
</div>
<div class="column is-8">
<div class="is-size-6">
<text class="has-text-white" style='cursor:initial;'><?=htmlentities(filter($status['status']))?></text></div>
<p class='has-text-white'>Posted by: <a href="/profile/<?=$post['username']?>"><span class="usercontent"><?=$post['username']?></span></a>, <span class="tooltip is-tooltip-top" data-tooltip="<?=date("M d Y h:i a", $status['time'])?>"><?=time_elapsed2($status['time']);?></span></p>
</div>
</div>
<?php
}
?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</section>
<script>
if ( window.history.replaceState ) {
window.history.replaceState( null, null, window.location.href );
}
</script>
<?php
footer();
?>