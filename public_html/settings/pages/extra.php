<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
if(!isset($_SESSION['id'])){
die(header("Location: /login/"));
}
?>
<h1 class="title has-text-white is-centered">Coming Soon</h1>
<?php
/*
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
if(!isset($_SESSION['id'])){
  die(header("Location: /login/"));
}

switch($user['email']){
  case '0':
    $email = "You do not have a Brickorzo Plus subscription.";
    break;
  default:
    $email = "Subscribed. Will expire on ".date("d/m/Y h:i:s a").".";
}
?>
<p class="has-text-white is-pulled-left">Membership Status: <?=$email?></p><br>
<p class="label has-text-white is-pulled-left">Cancel Membership:</p><br><br><br>
<button id="submit" class="button is-danger is-pulled-left">Cancel (This action is irreversable)</button>
<p class="help is-pulled-left" id="message"></p><br>
<script>
  $("#submit").click(function(resp){
    $.post("/settings/post/update-bio.php", {
      bio: $("#bio").val(),
      token: $("#token").val()
    }).done(function(resp) {
      if (resp != "Successfully updated bio!") {
        $("#message").addClass("is-danger")
        $("#message").text(resp) 
      } else {
        $("#message").addClass("is-success") 
        $("#message").text(resp) 
      }; 
    }); 
  });

  function toggleModal() {
    var modal = $('.modal');
    modal.toggleClass('is-active');
  }

  function closeModal() {
    var modal = $('.modal');
    modal.toggleClass('is-active');
  }
</script>
*/
?>