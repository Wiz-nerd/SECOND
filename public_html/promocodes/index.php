<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
$pageName = "Redeem";
include($_SERVER['DOCUMENT_ROOT'] . "/config/header.php");
if(!isset($_SESSION['id'])){
	header("Location: /login/");
}
?>
<div class="container">
<div class="content">
<div style="margin:auto;" class="column is-7">
<div class="box">
<h3 class="title has-text-white">Redeem a promocode</h3>
<text class="has-text-white">Promocodes are a special code given during Brickorzo events, such as seasonal events, livestreams, YouTube videos, and more.</text>
<div style="height:15px;"></div>
<div class="field has-addons">
<input id="code" class="input is-dark" placeholder="Enter your promocode">
<button id="redeem" class="button is-success">Redeem</button>
</div>
<p class="help is-danger" id="msg"></p>
<p class="help is-success" id="su"></p>

</div>
</div>
</div>
</div>
</div>
<?=footer()?>
<script>
$("#redeem").click(function(){
	$.post("/promocodes/post", {
		code: $("#code").val()
	}).done(function(resp){
		if(resp != "Successfully redeemed item!"){
			$("#msg").text(resp)
			setTimeout(function(){
				$("#msg").empty()
			}, 4000)
		}else{
			$("#su").text(resp)
			$("#redeem").prop("disabled", true)
		}
	})
})
</script>