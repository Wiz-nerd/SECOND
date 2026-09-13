<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if($user['rank'] == 0){
  unset($_SESSION['admin']);
  header("Location: /admin/login");
}

if(!isset($_SESSION['id'])){
  header("Location: /login/");
}

if(!isset($_GET['id']) || isset($_GET['id']) == null || !is_numeric($_GET['id']) || userInfoNumI($_GET['id'])->num_rows == 0){
  die(header("Location: /error/code/404"));
}

$pageName = "Ban ".userInfoNumI($_GET['id'])->fetch_assoc()['username']."";
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
?>
<div class="container">
  <div class="content">
    <div style="margin:auto;" class="column is-7">
      <div class="box">
        <h3 class="title has-text-white">Ban <?=userInfoNumI($_GET['id'])->fetch_assoc()['username']?></h3>
        <text class="has-text-white">Before doing moderation actions, please review our <a href="/legal/terms">Terms Of Service</a> to prevent any false bans or terminations.</text>
        <div style="height:15px;"></div>
        <label class="label has-text-white">Auto-Fill (Optional)</label>
        <div class="select is-dark">
          <select id="temp">
            <option value="c">None</option>
            <option value="cf">Coinfarming/Alt-Hoarding</option>
            <option value="sp">Spamming</option>
            <option value="hb">Harassement/Bullying</option>
            <option value="im">Impersonation</option>
            <option value="bs">Buying/selling accounts/items</option>
            <option value="rd">Raiding</option>
          </select>
        </div>
        <div style="height:15px;"></div>
        <label class="label has-text-white">Ban Reason</label>
        <input class="input is-dark" id="reason" placeholder="Breaking Rules">
        <div style="height:15px;"></div>
        <label class="label has-text-white">Ban Length</label>
        <div class="select is-dark">
          <select id="length">
            <option value="21600">6 hours</option>
            <option value="43200">12 hours</option>
            <option value="86400">1 day</option>
            <option value="259200">3 days</option>
            <option value="604800">7 days</option>
            <option value="1209600">14 days</option>
            <option value="26280000">30 days</option>
            <option value="term">Termination</option>
          </select>
        </div>
        <p class="help is-danger" id="msg"></p>
        <button id="submit" class="button is-success">Ban User</button>
      </div>
    </div>
  </div>
</div>
<?=footer()?>
<script>
  $("#temp").change(function(){
    switch(this.value){
      case "cf":
        $("#reason").val("Coinfarming/Alt-hoarding is not allowed. You have been terminated.")
        $("#length").val('term').change()
        break;
      case "sp":
        $("#reason").val("Spamming is not allowed on Brickorzo.")
        $("#length").val('21600').change()
        break;
      case "hb":
        $("#reason").val("Harassement and bullying is not permitted on Brickorzo.")
        $("#length").val('259200').change()
        break;
      case "im":
        $("#reason").val("Impersonating other users is not allowed, as it could damage their reputation if used maliciously")
        $("#length").val('term').change()
        break;
      case "bs":
        $("#reason").val("Buying/selling accounts and/or items is prohibited. You and the seller/buyer have been terminated")
        $("#length").val('term').change()
        break;
      case "rd":
        $("#reason").val("Raiding is not permitted on Brickorzo.")
        $("#length").val('term').change()
        break;
    }
  })
  
  $("#submit").click(function(){
    $.post("/admin/posts/moderation.php", {
      reason: $("#reason").val(),
      length: $("#length").val(),
      user: <?=userInfoNumI($_GET['id'])->fetch_assoc()['id']?>
      
    }).done(function(resp){
    	if(resp != "succ"){
         $("#msg").text(resp) 
        }else{
         $("#msg").text("Successfully banned user") 
        }
    })
  })
</script>