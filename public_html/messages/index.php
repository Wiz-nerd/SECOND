<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$pageName = "Messages";
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
include($_SERVER['DOCUMENT_ROOT'] . '/messages/msgBackend.php');
if(!isset($_SESSION['id'])){
	die(header("Location: /login/"));
}
?>
<div class="section">
<div class="container">
<div class="content">
<div class="columns">
<div class="column is-4">
<div class="box">
<h3 class="subtitle has-text-white is-centered">Latest Messages Sent</h3><hr>
<?php foreach ($sentResults as $msg) { 
$sned = userInfo($msg['reciever']);
?>
<div>
<text class="has-text-white has-text-weight-bold truncate"><?=filter(htmlentities($msg['title']))?></text>
<p class="has-text-white">Sent to <a href="/profile/<?=$sned['username']?>"><?=$sned['username']?></a></p>
</div>
<?php } ?>
</div>
</div>
<div class="column is-8">
<div class="box">
<div style="margin-top:-15px;" class="tabs is-toggle is-centered is-fullwidth">
<ul>
<li onclick="getMsgs(1)"><a><span>Inbox</span></a></li>
<li onclick="getMsgs(2)"><a><span>Sent</span></a></li>
<li onclick="getMsgs(3)"><a><span>History</span></a></li>
</ul>
</div>
<div id="messages"></div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php footer(); ?>
<script>
$(document).ready(function(){
$("#messages").load("/messages/get-msgs?sort=inbox");
})

function getMsgs(num){
switch(num){
case 1:
$("#messages").load("/messages/get-msgs?sort=inbox");
break;
case 2:
$("#messages").load("/messages/get-msgs?sort=sent");
break;
case 3:
$("#messages").load("/messages/get-msgs?sort=history");
break;
default:

}
}
</script>