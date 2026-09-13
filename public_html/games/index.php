<?php
$pageName = "Games";
include('../config/param.php');
include('../config/header.php');
if($user["sub"] != 0){
  include('hi.php');
}else{
?>
<center>
<div class="column is-6">
<div class="box">
	<i class="fas fa-gamepad has-text-white is-size-1"></i>
	<h1 class="title has-text-white">Games are coming soon!</h1>
	<p class="subtitle has-text-white">
    If you would like to play games early, you must purchase a subscription from the Discord.
	</p>
</div>
</div>
<?php
}
footer();
?>