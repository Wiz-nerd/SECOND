<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
?>
<div class="container">
	<div class="content">
		<div class="columns">
			<div class="column is-7">
				<div class="box">
					<h1 class="has-text-white title">Create Item</h1>
					<input id="name" class="input is-dark" placeholder="Item Name">
					<div style="height:10px"></div>
					<textarea id="desc" class="textarea is-dark" placeholder="Item Description"></textarea>
					<div style="height:10px"></div>
					<input type="number" id="price" class="input is-dark" placeholder="Item Price">
					<div style="height:10px"></div>
					<label class="label has-text-white">Onsale?</label>
					<div class="select is-dark">
						<select name="status" id="status">
							<option value="onsale">Yes</option>
							<option value="offsale">No</option>
						</select>
					</div><div style="height:10px;"></div>
					<label class="label has-text-white">Item Type</label>
					<div class="select is-dark">
						<select id="type">
							<option value="hat">Hat</option>
							<option value="face">Face</option>
							<option value="tool">Tool</option>
						</select>
					</div><div style="height:10px;"></div>
					<div id="file-js-example" class="file is-dark has-name">
						<label class="file-label">
							<input class="file-input" type="file" id="img">
							<span class="file-cta">
								<span class="file-icon">
									<i class="fas fa-upload"></i>
								</span>
								<span class="file-label">
									Upload Item Image
								</span>
							</span>
							<span class="file-name has-text-white is-border-black">
								No file selected
							</span>
						</label>
					</div><div style="height:10px;"></div>
					<p class="help is-danger" id="error"></p>
					<button id="upload" class="button is-success">Upload</button>
				</div>
			</div>
			<div class="column is-5">
				<div class="box">
					<h2 class="has-text-white title">Avatar Template</h2>
					<div class="is-centered">
						<a href="/assets/images/avatar.png" download="Avatar.png">
							<img src="/assets/images/avatar.png"></img></a>
						</div></div>
						<div class="box">
							<h3 class="has-text-white subtitle">Before Uploading:</h3>
							<ul>
								<li class="has-text-white">Make sure the size of your item is 122x274</li>
								<li class="has-text-white">Make sure your item is transparent</li>
								<li class="has-text-white">Only upload items made with the official template</li>
								<li class="has-text-white has-text-weight-bold">Make sure your item is compliant with our TOS</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
	var fileInput = document.querySelector('#file-js-example input[type=file]')
	fileInput.onchange = () => {
		if (fileInput.files.length > 0) {
			var fileName = document.querySelector('#file-js-example .file-name')
			fileName.textContent = fileInput.files[0].name;
		}
	}

	$("#upload").click(function(){
		$("#upload").attr("disabled", true)
		var formData = new FormData()
		formData.append('name', $("#name").val())
		formData.append('desc', $("#desc").val())
		formData.append('price', $("#price").val())
		formData.append('type', $("#type").val())
		formData.append('status', $("#status").val())
		formData.append('img', $("#img")[0].files[0])
		jQuery.ajax({
			type: "POST",
			cache: false,
			url: "/admin/posts/upload",
			data: formData,
			enctype: 'multipart/form-data',
			processData: false,
			contentType: false
		}).done(function(resp) {

			if(resp != "succ") {
				$("#error").text(resp)
				setTimeout(function(){
					$("#upload").attr("disabled", false);
					$("#error").empty()
				}, 2000)

			}else{
				window.location='/shop/'
			}
	}).fail(function() {
		alert("There was a problem handling your request.")
	})
})
</script>