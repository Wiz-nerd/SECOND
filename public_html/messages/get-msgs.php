<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
//include($_SERVER['DOCUMENT_ROOT'] . '/config/helper.php');
if(!isset($_GET['sort']) || $_GET['sort'] == null){
die("null");
}

switch($_GET['sort']){
case 'inbox':
$stmt = $conn->prepare("SELECT * FROM `msgs_brz` WHERE `reciever`=? AND `read`=? ORDER BY `id` DESC");
$stmt->bind_param("ii", $user['id'], $o);
$stmt->execute();
$results = $stmt->get_result();
$text = "From";
$text2 = "Recieved";
$error = "You do not have any incoming messages";
break;
case 'sent':
$stmt = $conn->prepare("SELECT * FROM `msgs_brz` WHERE `sender`=? ORDER BY `id` DESC");
$stmt->bind_param("i", $user['id']);
$stmt->execute();
$results = $stmt->get_result();
$text = "To";
$text2 = "Sent";
$error = "You have not sent any messages";
break;
case 'history':
$stmt = $conn->prepare("SELECT * FROM `msgs_brz` WHERE `reciever`=? AND `read`=? ORDER BY `id` DESC");
$stmt->bind_param("ii", $user['id'], $i);
$stmt->execute();
$results = $stmt->get_result();
$text = "From";
$text2 = "Recieved";
$error = "You have not read any messages";
break;
default:
die();
}

if(mysqli_num_rows($results) != 0){
foreach($results as $message){
switch($_GET['sort']){
case 'inbox':
$author = userInfo($message['sender']);
break;
case 'sent':
$author = userInfo($message['reciever']);
break;
case 'history':
$author = userInfo($message['sender']);
break;
default:
die();
}

if($message['read'] == 0){
$icon = "fas fa-eye-slash";
}else{
$icon = "fas fa-eye";
}

?>
<div class="box content">
<article class="post">
<div class="media">
<div class="media-content">
<div class="content">
<a href="/messages/view/<?=$message['id']?>" <h4="" class="has-text-white"><?=filter(htmlentities($message['title']))?></a>
<p class="has-text-white">
<?=$text?>: <a href="/profile/<?=$author['username']?>"><?=$author['username']?></a>. <?=$text2?> <?=time_elapsed2($message['time'])?> &nbsp;
</p>
</div>
</div>
<div class="media-right">
<span class="has-text-grey-light"><i class="<?=$icon?>"></i>
</div>
</div>
</article>
</div>
<?php
}
}else{
	die("<p class='has-text-white is-centered'>$error</p>");
}
?>