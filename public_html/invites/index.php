<?php
$pageName = "Invites";
include("{$_SERVER['DOCUMENT_ROOT']}/config/param.php");
include("{$_SERVER['DOCUMENT_ROOT']}/config/header.php");

if(!isset($_SESSION["id"])){
  die(header("Location: /login/"));
}
?>
<div class="section">
  <div class="container">
    <div class="content">
      <div class="columns">
        <div class="column is-7">
          <div class="box">
            <h2 class="title has-text-white mb-3">Invitation Program</h2><hr>
            <p class="has-text-white mb-3">Reward yourself while inviting your friends to our platform with the Invitation Program! When you invite someone to our platform using your invitation link, you will get 5 Shards per user invited. And depending on how many users you have invited, you will get bigger rewards, such as profile badges, items, and more. All you need to do, is to copy your invitation link and send it to your friends, upon them registering, you will recieve your 5 Shards.</p>
            <?php if($user['invite_code'] == ""): ?>
            <button user="<?=$user['id']?>" id="invite" class="button is-success is-fullwidth">Get Invite Link</button>
            <?php else: ?>
            <div class="field has-addons">
              <p class="control is-expanded">
                <input class="input is-dark" disabled="disabled" value="<?=$site[5]?>/register/?invite=<?=$user['invite_code']?>" id="invite" onclick="e()">
              </p>
              <p class="control">
                <button class="button is-success" onclick="getInviteLink()">Copy To Clipboard</button>
              </p>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <div class="column is-5">
          <div class="box">
            <h3 class="title has-text-white mb-3">Recent Users Invited</h3><hr>
            <div class="columns">
              <div class="column is-3">
                <a href="/profile/kar"><img src="/assets/images/avatar.png"></a>
              </div>
              <div class="column is-9">
                <a href="/profile/kar"><text class="has-text-white">kar</text></a><br>
                <text class="has-text-white">Joined 05/08/2022</text>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?=footer()?>
<script>
  function getInviteLink(){
    var text = $("#invite").val()
    navigator.clipboard.writeText(text)
  }
  
  $("#invite").click(function(){
    $.post("/invites/gen-code", {
     $("#invite[]") 
    })
  })
</script>