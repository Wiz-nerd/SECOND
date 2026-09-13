<?php
$pageName = "Register";
include('../config/param.php');
include('../config/header.php');
include('../register/functions.php');
if(isset($_SESSION['id'])){
    header("Location: /home/");
}
?>
<center>
<div class="column is-6">
<?php
foreach($errors as $error){
	echo"<div class='notification is-danger has-text-white'>
	  $error
	</div>";
}
?>
<div class="box is-centered">
<h2 class="title has-text-white">Time to begin your adventure!</h2>
<form method='post'>
<label class="label has-text-white" style='display:flex;'>Username (e.g: <?=RandName();?>)</label>
<input type='text' name='uname' class='input is-dark'>
<label class="label has-text-white" style='display:flex;'>Password</label>
<input type='password' name='pwd1' class='input is-dark'>
<label class="label has-text-white" style='display:flex;'>Confirm Password</label>
<input type='password' name='pwd2' class='input is-dark'>
<br><br>
<div style="display:flex;" class="h-captcha" data-sitekey="efb39195-fd5f-4f46-b75d-27ed482d4489"></div>
<br><br>
<p class='is-centered has-text-white'>The moment you register your account, you automatically agree to the
<a href='/legal/terms' target="_blank">Terms of Service.</a></p><br>
<button type='submit' class="button is-centered is-black">Sign Up!</button>
</form>
</div>
</div>
<?php
footer();
?>
<script>
if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>