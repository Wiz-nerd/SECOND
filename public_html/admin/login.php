<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$pageName = "Admin Login";
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');



if(!isset($_SESSION['id'])){
	header("Location: /login/");
}
?>
<div class="container">
<div class="content">
<div style="margin:auto;" class="column is-6">
<div class="box">
<h3 class="title has-text-white is-centered">Admin Login</h3>
<p class="help is-danger" id="error"></p>
<input type="password" id="passcode" class="input is-dark" placeholder="Admin Passcode">
<div style="height:10px;"></div>
<button id="submit" class="button is-fullwidth is-danger">Login</button>
</div>
</div>
</div>
</div>
<?=footer()?>
<script>
$("#submit").click(function(){
	$.post("/admin/posts/admin-login.php", {
		code: $("#passcode").val()
	}).done(function(resp){
		if(resp != "succ"){
           $("#error").text(resp)
           setTimeout(function(){
              $("#error").empty();
           }, 2000)
		}else{
			window.location='/admin/'
		}
	})
})
</script>