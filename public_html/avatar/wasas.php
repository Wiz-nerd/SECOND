<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
$pageName = "Edit Avatar";
include($_SERVER['DOCUMENT_ROOT'] . "/config/header.php");
if(!isset($_SESSION['id'])){
    die(header("Location: /login/"));
}
$types = [
"Hats" => "hat",
"Faces" => "face",
"Tools" => "tool",
"Shirts" => "shirt",
"Pants" => "pant"
];
?>
<section class="section">
<div class="container">
<div class="columns">
<div class="column is-3">
<div class="box">
<div id="avatar" class="is-centered"></div>
<div style="height:10px"></div>
<div class="is-centered">
<button onclick="redraw()" class="button is-success is-small">Redraw</button>
<button onclick="reset()" class="button is-danger is-small">Reset</button>
</div>
</div>
</div>
<div class="column is-8">
<div class="box">
<div class="tabs is-centered is-small"><ul>
<?php foreach($types as $name => $link){ ?>
<li><a onclick="getPage('<?=$link?>', 0)"><?=$name?></a></li>
<?php } ?>
</ul></div>
<div id="inventory"></div>
</div>
<div class="box">
<p class="subtitle has-text-white">Currently Wearing</p>
<div id="wearing"></div>
</div>
</div>
</div>
</div>
</section>
<script>
$(document).ready(function(){
	$("#avatar").load("/avatar/render-testing/get-avatar.php");
    $("#inventory").load("/avatar/render-testing/get-inv.php?sort=hat&page=undefined");
    $("#wearing").load("/avatar/render-testing/wearing.php");
})

function getPage(type, page) {
	$("#inventory").load("/avatar/render-testing/get-inv.php?sort="+type+"&page="+page);
};

function redraw(){
    $("#avatar").load("/avatar/render-testing/avatarRender.php?draw");
    $("#wearing").load("/avatar/render-testing/wearing.php");
}

function wear(id){
    $("button[name="+id+"]").prop("disabled", true)
    $("#avatar").load("/avatar/render-testing/avatarRender.php?render="+id+"");
    $("#wearing").load("/avatar/render-testing/wearing.php");
    redraw();
}

function remove(id){
    $("#avatar").load("/avatar/render-testing/avatarRender.php?remove="+id+"");
    $("#wearing").load("/avatar/render-testing/wearing.php");
    redraw();
}

function reset(){
	$("#avatar").load("/avatar/render-testing/avatarRender.php?reset");
	$("#wearing").load("/avatar/render-testing/wearing.php");
}
</script>
<?=footer();?>