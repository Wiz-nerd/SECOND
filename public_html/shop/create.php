<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$pageName = "Create";
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
include($_SERVER['DOCUMENT_ROOT'] . '/shop/upload.php');
if(!isset($_SESSION['id'])){
	die(header("Location: /login/"));
}
?>
<section class="section">
<div class="container">
<div class="content">
<?php foreach ($errors as $error) { ?>
<div class="notification is-danger has-text-white">
<?=$error?>
</div>
<?php } ?>
<div class="columns">
<div class="column is-7">
<div class="box">
<h1 class="has-text-white title">Create Item</h1>
<form method="post" enctype="multipart/form-data">
<input name="name" class="input is-dark" placeholder="Item Name">
<div style="height:10px"></div>
<textarea name="desc" class="textarea is-dark" placeholder="Item Description"></textarea>
<div style="height:10px"></div>
<input type="number" name="price" class="input is-dark" placeholder="Item Price">
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
<select name="type">
<option value="shirt">Shirt</option>
<option value="pant">Pants</option>
</select>
</div><div style="height:10px;"></div>
<div id="file-js-example" class="file is-dark has-name">
<label class="file-label">
<input class="file-input" type="file" name="img">
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
<button type="submit" class="button is-success">Upload</button>
</form>
</div>
</div>
<div class="column is-5">
<div class="box">
<h2 class="has-text-white title">Templates</h2>
<div class="is-centered">
<a href="/assets/images/shop/ShirtTemplate.png" download="ShirtTemplate.png">
<img src="/assets/images/shop/ShirtTemplate.png"></img></a>
<a href="/assets/images/shop/PantsTemplate.png" download="PantsTemplate.png">
<img src="/assets/images/shop/PantsTemplate.png"></img></a>
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
</section>
<?php
footer();
?>
<script>
const fileInput = document.querySelector('#file-js-example input[type=file]');
fileInput.onchange = () => {
if (fileInput.files.length > 0) {
const fileName = document.querySelector('#file-js-example .file-name');
fileName.textContent = fileInput.files[0].name;
}
}

if ( window.history.replaceState ) {
window.history.replaceState( null, null, window.location.href );
}
</script>