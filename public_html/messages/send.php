<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(!isset($_GET['id']) || $_GET['id'] == null || mysqli_num_rows(userInfoNumI($_GET['id'])) == 0 || $_GET['id'] == $user['id']){
die(header("Location: /error/code/404"));
}
$reciever = userInfo($_GET['id']);
$pageName = "Message ".htmlentities($reciever['username'])."";
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
if(!isset($_SESSION['id'])){
	die(header("Location: /login/"));
}
?>
<div class="container">
<div class="content">
<div class="column is-7 is-centered">
<div class="box">
<h3 class="title has-text-white">Send message to <?=$reciever['username']?></h3>
<input class="input is-dark" id="title" placeholder="Title (Maximum of 40 characters)">
<div style="height:5px;"></div>
<textarea class="textarea is-dark" id="body" placeholder="Body (Maximum of 400 characters)"></textarea>
<div style="height:5px;"></div>
<button style="background:#fe8447;" id="button" class="button is-success is-fullwidth">Send</button>
<p id="succ" class="help is-success is-pulled-left"></p>
<p id="errors" class="help is-danger is-pulled-left"></p>
<br>
</div>
</div>
</div>
</div>
<?=footer();?>
<script>
$("#button").click(function(){
	$.post("/messages/post",{
		reciever: <?=$reciever['id']?>,
		title: $("#title").val(),
		body: $("#body").val()
	}).done(function(resp){
		if(resp != "Successfully sent message!"){
			$("#errors").text(resp)
			setTimeout(function(){
				$("#errors").empty()
			}, 3000)
		}else{
			$("#button").prop("disabled", true)
			$("#succ").text(resp)
		}
	})
})
</script>