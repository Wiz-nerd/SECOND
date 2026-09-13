<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$getSiteSettings = $conn->prepare("SELECT * FROM `brz_site_conf`");
$getSiteSettings->execute();
$setResult = $getSiteSettings->get_result();
$settings = $setResult->fetch_assoc();
?>
<div class="columns">
	<div style="margin:auto;" class="column is-5">
		<h3 class="has-text-white">Banner Settings</h3>
		<p id="error1" class="help is-danger"></p>
		<p id="succ1" class="help is-success"></p>
		<label class="label has-text-white">Banner Text (Leave blank to make banner invisible)</label>
		<input id="banner" value="<?=$settings['banner']?>" class="input is-dark" placeholder="Banner Text">
		<div style="height:5px;"></div>
		<label class="label has-text-white">Banner Color</label>
		<div class="select is-dark">
			<select id="color">
				<option value="has-background-success">Green</option>
				<option value="has-background-danger">Red</option>
				<option value="has-background-link">Blue</option>
				<option value="has-background-black">Black</option>
				<option value="has-background-warning">Yellow</option>
			</select>
		</div><br><div style="height:10px;"></div>
		<button id="banSub" class="button is-success">Save Changes</button>
	</div>
	<div style="margin:auto;" class="column is-5">
		<h3 class="has-text-white">Other Settings</h3>
		<p id="error2" class="help is-danger"></p>
		<p id="succ2" class="help is-success"></p>
		<label class="label has-text-white">Maintenance?</label>
		<div class="select is-dark">
			<select id="maintain">
				<option value="yes">Yes</option>
				<option value="no">No</option>
			</select>
		</div><div style="height:10px;"></div>
		<label class="label has-text-white">Maintenance Code</label>
		<input id="code" class="input is-dark" placeholder="Maintenance Code" value="<?=$settings['maintenance_code']?>">
		<div style="height:10px;"></div>
		<button id="siteSub" class="button is-success">Save Changes</button>
	</div>
</div>
<script>
$("#banSub").click(function(){
	$.post("/admin/posts/site", {
		banner: $("#banner").val(),
		color: $("#color").val()
	}).done(function(resp){
		if(resp != "Successfully updated banner!"){
			$("#error1").text(resp);
			setTimeout(function(){
                $("#error1").empty();
			}, 2000)
		}else{
			$("#succ1").text("Successfully updated banner!");
		}
	})
})

$("#siteSub").click(function(){
	$.post("/admin/posts/site", {
		maintain: $("#maintain").val(),
		code: $("#code").val()
	}).done(function(resp){
		if(resp != "Successfully updated site!"){
			$("#error2").text(resp);
			setTimeout(function(){
                $("#error2").empty();
			}, 2000)
		}else{
			$("#succ2").text("Successfully updated site!");
		}
	})
})
</script>