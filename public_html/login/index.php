<?php
$pageName = "Login";
include('../config/param.php');
include('../config/header.php');
include('../login/functions.php');
if(isset($_SESSION['id'])){
header("Location: /home/");
die();
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
<h2 class="title has-text-white">Welcome back!</h2>
<form method='post'>
<label class="label has-text-white" style='display:flex;'>Username</label>
<input type='text' name='uname' class='input is-dark'>
<label class="label has-text-white" style='display:flex;'>Password</label>
<input type='password' name='pwd' class='input is-dark'>
<br><br>
<p class='is-centered has-text-white'>Don't have an account? Register 
<a href='/register/'>here!</a></p><br>
<button type='submit' class="button is-centered is-black">Login!</button>
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