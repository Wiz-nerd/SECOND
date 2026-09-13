<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$pageName = "Shop";
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
include($_SERVER['DOCUMENT_ROOT'] . '/shop/itemBackend.php');
$types = [
"Hats" => "hat",
"Faces" => "face",
"Tools" => "tool",
"Shirts" => "shirt",
"Pants" => "pant"
];
?>
<div class="container">
<button onclick="window.location='/shop/create'" class="button is-success is-pulled-right">Create</button>
<p class="title has-text-white">Shop</p>
<div class="box">
<div class="tabs is-centered">
<ul>
<?php foreach($types as $type => $cat){ ?>
<li>
<a onclick='LoadItems("<?=$cat?>")'>
<span><?=$type?></span>
</a>
</li>
<?php } ?>
</ul>
</div>
<div id='items'></div>
</div>
</div>
<?php
footer();
?>
<script>
$(document).ready(function(){
$("#items").load("/shop/get-items.php?sort=hat");
});

function LoadItems(cat){
$("#items").load("/shop/get-items.php?sort="+cat+"");
}

function getPage(page, cat){
$("#items").load("/shop/get-items.php?sort="+cat+"&page="+page+"");
}

</script>