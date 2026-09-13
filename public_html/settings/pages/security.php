<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
if(!isset($_SESSION['id'])){
die(header("Location: /login/"));
}

$getLogins = $conn->prepare("SELECT * FROM `brzlogins` WHERE `user_id`=? ORDER BY `id` DESC LIMIT 5");
$getLogins->bind_param("i", $user['id']);
$getLogins->execute();
$loginResult = $getLogins->get_result();
?>
<label class="label has-text-white is-pulled-left">Change Password</label><br>
<input type="password" id="pass" class="input is-dark" placeholder="Old Password" /><br><br>
<input type="password" id="pass2" class="input is-dark" placeholder="New Password" /><br><br>
<input type="password" id="pass3" class="input is-dark" placeholder="Confirm Password" /><br>
<p id="message" class="help is-pulled-left"></p><br>
<button id="submit" class="button is-success is-pulled-left">Change Password</button><br><br>
<hr>
<label class="label has-text-white is-pulled-left">Login History</label><br><br>
<?php foreach($loginResult as $login){ ?>
<text class="is-pulled-left has-text-white truncate">
	Login from <?=$login['os'] ." - ". $login['browser'] ." ". time_elapsed2($login['time']);?>
</text><br>
<?php } ?>
<script>
$("#submit").click(function(){
	$.post("/settings/post/change-password.php", { 
		p1: $("#pass").val(), 
		p2: $("#pass2").val(), 
		p3: $("#pass3").val() 
	}).done(function(resp){
		if(resp != "Successfully changed password!"){
			$("#message").addClass("is-danger")
			$("#message").text(resp)
		}else{
			$("#message").addClass("is-success")
			$("#message").text(resp)
		}
	})
})
</script>