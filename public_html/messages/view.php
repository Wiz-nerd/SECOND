<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$getMsg = $conn->prepare("SELECT * FROM `msgs_brz` WHERE ((`reciever`=?) OR (`sender`=?)) AND `id`=?");
$getMsg->bind_param("iii", $user['id'], $user['id'], $_GET['id']);
$getMsg->execute();
$msgResults = $getMsg->get_result();
if(!isset($_GET['id']) || $_GET['id'] == null || mysqli_num_rows($msgResults) == 0){
  die(header("Location: /error/code/404"));
}

$message = $msgResults->fetch_assoc();

switch($message['read']){
  case 0:
    if($message['reciever'] == $user['id']){
      $stmt = $conn->prepare("UPDATE `msgs_brz` SET `read`=? WHERE `id`=?");
      $stmt->bind_param("ii", $i, $message['id']);
      $stmt->execute();
    }
    break;
  default:
}

$pageName = htmlentities($message['title']);
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
$sender = userInfo($message['sender']);
if(!isset($_SESSION['id'])){
  die(header("Location: /login/"));
}
?>
<div class="modal">
  <div onclick="closeModal()" class="modal-background"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title has-text-white">Reply to this message</p>
      <button class="delete" onclick="closeModal()" aria-label="close"></button>
    </header>
    <section class="modal-card-body">
      <p class="help is-danger is-centered" id='err'></p>
      <p class="help is-success is-centered" id='succ'></p>
      <form method='post' id='create'>
        <label class="label has-text-white" style='display:flex;'>Body</label>
        <textarea type='text' id='body' class='textarea is-dark'></textarea>
        <br>
        <p class='is-centered has-text-white'>Make sure your reply is compliant with our
          <a href='/legal/terms'>Terms of Service.</a></p><div style="height:20px"></div>
        </section>
      <footer class="modal-card-foot">
        <button type='submit' id="e" class="button is-centered is-black">Reply!</button></form>
      </footer>
  </div>
</div>
<div class="container">
  <div class="content">
    <div class="column is-7 is-centered">
      <h3 class="title has-text-white is-pulled-left"><?=htmlentities(filter($message['title']))?></h3>
      <div style="height:50px"></div>
      <div class="box">
        <div class="columns is-mobile">
          <div class="column is-5-mobile is-3-tablet is-3-desktop">
            <div class="has-text-centered">
              <p class="has-text-white">
                <a href="/profile/<?=$sender['username']?>"><img width="100" height="100" src="<?=$sender['avatar_img']?>"<br></a>
                <a class="has-text-white" href="/profile/<?=$sender['username']?>"><?=$sender['username']?></a></p>
            </div>
          </div>
          <div class="column is-7-mobile is-9-tablet is-9-desktop">
            <p class="has-text-white is-pulled-left"><?=nl2br(htmlentities(filter($message['body'])))?></p>
          </div>
        </div>
      </div>
      <button onclick="toggleModal()" class="button is-success">Reply</button>
    </div>
  </div>
</div>
<?=footer()?>
<script>
  function toggleModal() {
    var modal = $('.modal');
    modal.toggleClass('is-active');
  }

  function closeModal() {
    var modal = $('.modal');
    modal.toggleClass('is-active');
  }

  $("#create").submit(function(e){
    e.preventDefault();
    $.post("/messages/reply", {
      body: $("#body").val(),
      topic: <?=$message['id']?>
    }).done(function(resp){
      if(resp != "success") {
        $("#err").text(resp);
      } else {
        $("#succ").text("Successfully replied to the message!");
        $("#e").prop("disabled", true);
      };
    });
  });
</script>