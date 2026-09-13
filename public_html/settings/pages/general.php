<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
if(!isset($_SESSION['id'])){
die(header("Location: /login/"));
}

$email = $user['email'];
$em = explode("@",$email);
$name = $em[0];
$len = strlen($name);
$showLen = floor($len/2);
$str_arr = str_split($name);
for($ii=$showLen;$ii<$len;$ii++){$str_arr[$ii] = '*';}
$em[0] = implode('',$str_arr); 
$new_name = implode('@',$em);

switch($user['email']){
case '0':
$email = "You do not have an email added!";
break;
default:
$email = $new_name;
}
?>
<p class="has-text-white is-pulled-left">ID: <?=$user['id']?></p><br>
<p class="has-text-white is-pulled-left">Username: <?=$user['username']?> <i onclick="toggleModal()" class="fas fa-pencil-alt"></i></p><br>
<p class="has-text-white is-pulled-left">Next Currency Reward: <?=date("m/d/Y h:i:s a", $user['daily_shards'])?></p><br>
<hr>
<label class="label has-text-white is-pulled-left">Change Bio</label><br>
<textarea id="bio" class="textarea is-dark"><?=$user['bio']?></textarea>
<input type="hidden" id="token" value="<?=$_SESSION['token']?>"> 
<p class="help is-pulled-left" id="message"></p><br>
<button id="submit" type="submit" class="button is-success is-pulled-left">Update</button><br><br>
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
  
$("#changeUsername").click(function(resp){
	$.post("/settings/post/change-username.php", {
		username: $("#newUsername").val() 
	}).done(function(resp) {
		if (resp != "Successfully changed username!") {
			$("#errorMsgs").addClass("is-danger")
		    $("#errorMsgs").text(resp)
		} else {
            $("#errorMsgs").removeClass("is-danger") 
			$("#errorMsgs").addClass("is-success") 
			$("#errorMsgs").text(resp) 
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