<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/config/helper.php');
$thread = threadInfo($_GET['id']);
$getReplies = $conn->prepare("SELECT * FROM `forum_replies` WHERE `thread`=?");
$getReplies->bind_param("i", $thread['id']);
$getReplies->execute();
$reply = $getReplies->get_result();
if(mysqli_num_rows($reply) == 0){
  echo"<center><p class='has-text-white'>There are no replies :(</p><br />";
  die();
}
$e = $reply->fetch_assoc();
foreach($reply as $replies){
  $creator = userInfo($replies['creator']);
  if($creator['last_online'] + 180 > time()){
    $color = "green";
  }else{
    $color = '#A9A9A9';
  }
?>
<div id="postContainer" class="column">
  <div class="box">
    <div class="columns is-mobile">
      <div class="column is-5-mobile is-3-tablet is-3-desktop">
        <div class="has-text-centered">
          <p class="has-text-white">
            <span style='color:<?=$color?>;'>●</span>
            <a class="has-text-white" href="/profile/<?=$creator['username']?>"><?=$creator['username']?></a></p>
          <a href="/profile/<?=$creator['username']?>"><img width="100" height="100" src="<?=$creator['avatar_img']?>"></a>
          <?php if($creator["membership"] != 0): ?>
          <p class="has-background-danger has-text-white is-size-7 mt-2"><b>Plus</b></p>
          <?php endif; ?>
          <p class="has-text-white is-size-7"><b>Posts</b>: <?=$creator['posts']?></p>
          <p class="has-text-white is-size-7"><b>Joined</b>: <?=date("Y/m/d", $creator['join_date'])?></p>
        </div>
      </div>
      <div class="column is-7-mobile is-9-tablet is-9-desktop">
        <p class="has-text-white"><b><i class="fas fa-clock"></i> Posted on</b>: <?=date("M d Y h:i:s a", $replies['time'])?></p>
        <p class="has-text-white usercontent"><?=nl2br(filter(htmlentities($replies['body'])))?></p>
        <?php
    if(isset($_SESSION['id'])){
      if($user['rank'] != 0){ ?>
        <a href="/forums/moderation?deleteReply=<?=$replies['id']?>" class="button is-danger">Delete Reply</a>
        <?php
                             } 
    }?>
      </div>
    </div>
  </div></div>
<?php
}
?>