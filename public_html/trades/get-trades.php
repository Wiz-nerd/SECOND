<?php
include("../config/param.php");
if(isset($_GET['get'])){
  switch($_GET['get']){
    case "incoming":
      $text = "Incoming Trade";
      $status = 'p';
      $getTrades = $conn->prepare("SELECT * FROM `brz_trades_brz_orzo_brz` WHERE `reciever`=? AND `status`=? ORDER BY `id` DESC");
      $getTrades->bind_param("is", $user['id'], $status);
      $getTrades->execute();
      $getTrade = $getTrades->get_result();
      break;
    case "outgoing":
      $text = "Outgoing Trade";
      $status = 'p';
      $getTrades = $conn->prepare("SELECT * FROM `brz_trades_brz_orzo_brz` WHERE `sender`=? AND `status`=? ORDER BY `id` DESC");
      $getTrades->bind_param("is", $user['id'], $status);
      $getTrades->execute();
      $getTrade = $getTrades->get_result();
      break;    
    case "history":
      $text = "Past Trade";
      $status = ['a','d'];
      $getTrades = $conn->prepare("SELECT * FROM `brz_trades_brz_orzo_brz` WHERE ((`status`=?) OR (`status`=?)) AND ((`sender`=? OR `reciever`=?) OR (`reciever`=? OR `sender`=?)) ORDER BY `id` ASC");
      $getTrades->bind_param("ssiiii", $status[0], $status[1], $user['id'], $user['id'], $user['id'], $user['id']);
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
          <a href='/trades/trade?id=<?=$trades['id']?>'>
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