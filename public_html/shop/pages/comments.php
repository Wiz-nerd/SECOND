<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
include($_SERVER['DOCUMENT_ROOT'] . '/shop/itemBackend.php');
if(!isset($_GET['id']) || empty($_GET['id']) || mysqli_num_rows(GetItem($_GET['id'])) == null){
  header("Location: /error/code/404");
}
$item = GetItem($_GET['id']);
$it = $item->fetch_assoc();
?>
<div class="box">
  <h2 class="title has-text-white">Comments</h2>
  <p id="commentMsg" class="help is-danger"></p>
  <textarea class="textarea is-dark" placeholder="What are your thoughts on this item?" id="comment"></textarea>
  <div style="height:10px"></div>
  <button class="button is-success" id="submit">Post Comment</button>
  <hr>
  <?php
  $getItemComments = $conn->prepare("SELECT * FROM `item_comments` WHERE `item_id`=? ORDER BY `id` DESC LIMIT 5");
  $getItemComments->bind_param("i", $it['id']);
  $getItemComments->execute();
  $commentResults = $getItemComments->get_result();
  foreach($commentResults as $comments){
    $poster = userInfo($comments['user_id']);
  ?>
  <div class="columns">
    <div class="column is-2 column-is-vcentered">
      <img style="width:65px;" src="<?=$poster['avatar_img']?>">
    </div>
    <div class="column is-8">
      <div class="is-size-6">
        <text class="has-text-white" style="cursor:initial;"><?=filter(htmlentities($comments['comment']))?></text></div>
      <p class="has-text-white">Posted by <a href="/profile/<?=$poster['username']?>"><span><?=$poster['username']?></a> on <?=date("j/m/Y", $comments['time'])?></span></a></p>
</div>
</div>
<?php
    }  
?>
</div>