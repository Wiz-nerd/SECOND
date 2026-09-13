<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$getThread = $conn->prepare("SELECT * FROM `forum_threads` WHERE `id`=?");
$getThread->bind_param("i", $_GET['id']);
$getThread->execute();
$threadYes = $getThread->get_result();
if(mysqli_num_rows($threadYes) > 0){
}else{
  header("Location: /error/code/404");
}
$thread = $threadYes->fetch_assoc();
$pageName = htmlentities(filter($thread['title']));
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
$creator = userInfo($thread['creator']);
if($creator['last_online'] + 180 > time()){
  $color = "green";
}else{
  $color = '#A9A9A9';
}

if($thread['hidden'] == 1 && $user['rank'] == 0){
  header("Location: /error/code/404");
}
if(isset($_SESSION['id']) && $thread['locked'] != 1) { ?>
<div class="modal">
  <div onclick="closeModal()" class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title has-text-white">Reply to this thread</p>
      <button class="delete" onclick="closeModal()" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
      <p style='color:red;' id='err'></p>
      <form method='post' id='create'>
        <label class="label has-text-white" style='display:flex;'>Body</label>
        <textarea type='text' id='body' class='textarea is-dark'></textarea>
        <br>
        <p class='is-centered has-text-white'>By using our forums, you automatically agree to the
          <a href='/legal/terms' target="_blank">Terms of Service.</a></p><div style="height:20px"></div>
        </section>
      <footer class="modal-card-foot">
        <button type='submit' class="button is-centered is-black">Reply!</button></form>
      </footer>
  </div>
</div>
<?php } ?>
<div class="has-text-centered">
  <p class="subtitle has-text-white">
    <?=filter(htmlentities($thread['title']))?>
    <?php if($thread['locked'] != 0){ ?>
    <i class="fa fa-lock"></i>
    <?php } ?>
  </p>
</div>
<div class="container">
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
          <p class="has-text-white"><b><i class="fas fa-clock"></i> Posted on</b>: <?=date("M d Y h:i:s a", $thread['time'])?></p>
          <p class="has-text-white usercontent"><?=nl2br(htmlentities(filter($thread['body'])))?></p>
          <?php
  if(isset($_SESSION['id'])){
  if($user['rank'] != 0){ 
  switch($thread['locked']){
    case 0:
      $lok = "<button id=\"lock\" class=\"button is-danger\">Lock Thread</button> ";
      break;
    case 1:
      $lok = "<button id=\"unlock\" class=\"button is-danger\">Unlock Thread</button> ";
      break;
    default:
  }

  switch($thread['hidden']){
    case 0:
      $de = "<button id=\"delete\" class=\"button is-danger\">Delete Thread</button> ";
      break;
    case 1:
      $de = "<button id=\"show\" class=\"button is-danger\">Revert Thread Deletion</button> ";
      break;
    default:
  }

  echo $lok;
  echo $de;

} 
          }?>
        </div>
      </div>
    </div></div>
  <div id='replies'></div>
  <div class="has-text-centered">
    <?php if(isset($_SESSION['id']) && $thread['locked'] != 1) { ?>
    <button class="button is-dark is-medium" onclick="toggleModal()">Reply</button>
    <?php } ?>
  </div>
</div>
<?php
footer();
?>
<script>
  function toggleModal() {
    var modal = $('.modal');
    modal.toggleClass('is-active');
  }

  function closeModal() {
    var modal = $('.modal');
    modal.toggleClass('is-active');
  }

  $(document).ready(function() {
    var id = <?=$thread['id']?>;
    $.get("/forums/get-replies.php?id="+id+"", function(data){
     $("#replies").html(data) 
    })
  });

  <?php if($thread['locked'] != 1){ ?>
  $("#create").submit(function(e){
    e.preventDefault();
    $.post("/forums/reply_backend", {
      body: $("#body").val(),
      topic: <?=$thread['id']?>
    }).done(function(resp){
      if(resp != "success") {
        $("#err").text(resp);
      } else {
        var id = <?=$thread['id']?>;
        window.location = "/forums/thread/"+id+"";
      };
    });
  });
  <?php } ?>

  <?php 
  if(isset($_SESSION['id'])){
  if($user['rank'] != 0){ ?>
  $("#lock").click(function(){
    var id = <?=$thread['id']?>;
    window.location='/forums/moderation.php?lock='+id+'';
  })

  $("#unlock").click(function(){
    var id = <?=$thread['id']?>;
    window.location='/forums/moderation.php?unlock='+id+'';
  })

  $("#delete").click(function(){
    var id = <?=$thread['id']?>;
    window.location='/forums/moderation.php?delete='+id+'';
  })

  $("#show").click(function(){
    var id = <?=$thread['id']?>;
    window.location='/forums/moderation.php?show='+id+'';
  })
  <?php }
  }?>
</script>