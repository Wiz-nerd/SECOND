<?php
include("../config/param.php");
if(isset($_GET['get'])){
  switch($_GET['get']){
    case "incoming":
      $text = "Incoming Trade";
      $getTrades = $conn->prepare("SELECT * FROM `brz_trading` WHERE `reciever`=? AND `status`=0 ORDER BY `id` DESC");
      $getTrades->bind_param("i", $user['id']);
      $getTrades->execute();
      $getTrade = $getTrades->get_result();
      break;
    case "outgoing":
      $text = "Outgoing Trade";
      $getTrades = $conn->prepare("SELECT * FROM `brz_trading` WHERE `sender`=? AND `status`=0 ORDER BY `id` DESC");
      $getTrades->bind_param("i", $user['id']);
      $getTrades->execute();
      $getTrade = $getTrades->get_result();
      break;    
    case "history":
      $text = "Past Trade";
      $getTrades = $conn->prepare("SELECT * FROM `brz_trading` WHERE `status`=1 AND ((`sender`=? OR `reciever`=?) OR (`reciever`=? OR `sender`=?)) ORDER BY `id` ASC");
      $getTrades->bind_param("iiii", $user['id'], $user['id'], $user['id'], $user['id']);
      $getTrades->execute();
      $getTrade = $getTrades->get_result();
      break;
    default:
      die("Invalid Request");
  }
}
?>
<?php foreach($getTrade as $trades){ 
$userer = userInfo($trades['sender']);
?>
<div class="box content">
  <article class="post">
    <div class="media">
      <div class="media-left">
        <img style="width:50px;" src="<?=$userer['avatar_img']?>">
      </div>
      <div class="media-content">
        <div class="content">
          <a href='/canada/view/<?=$trades['id']?>'>
            <p class='has-text-white'><?=$text?></p>
          </a>
          <p class='has-text-white'>
            Sent by: <a href="/profile/<?=$userer['username']?>"><?=$userer['username']?></a>. <?=time_elapsed2($trades['time'])?>
          </p>
        </div>
      </div>
    </div>
  </article>
</div>
<?php } ?>