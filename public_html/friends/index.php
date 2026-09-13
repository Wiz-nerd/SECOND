<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
$pageName = "Friend Requests";
include($_SERVER['DOCUMENT_ROOT'] . "/config/header.php");
if(!isset($_SESSION['id'])){
	header("Location: /login/");
}
?>
<div class="container">
	<div class="content">
		<div class="column is-9 is-centered">
			<h3 class="title has-text-white is-pulled-left">Friend Requests</h3>
			<div style="height:40px"></div>
			<div class="box">
				<?php include($_SERVER['DOCUMENT_ROOT'] . "/friends/get-requests.php"); ?>
			</div>
		</div>
	</div>
</div>
<?=footer()?>
<script>
$(document).ready(function(){
	$("#friends").load("/friends/get-requests");
})

$("#accept").click(function(){
	$.post("/friends/handler", {
		request: "accept", 
		friend: $("#accept").val()
	}).done(function(reply){
		if(reply != "succ"){
			console.log(reply)
		}else{
			$("#accept").hide();
			$("#decline").hide();
			document.location='/friends/';
		}
	 })
  })

$("#decline").click(function(){
	$.post("/friends/handler", {
		request: "remove",
		friend: $("#decline").val()
	}).done(function(reply){
		if(reply != "succ"){
			console.log(reply)
		}else{
			$("#decline").hide();
			$("#accept").hide();
			document.location='/friends/';
		}
	})
  })
</script>