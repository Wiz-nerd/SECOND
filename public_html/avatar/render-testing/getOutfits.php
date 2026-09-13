<?php
include("{$_SERVER["DOCUMENT_ROOT"]}/config/param.php");
if(!isset($_SESSION['id'])){
  die(); 
}

$getOutfits = $conn->prepare("SELECT * FROM `av_outfits_orzo` WHERE `user`=? ORDER BY `id` DESC");
$getOutfits->bind_param("i", $user["id"]);
$getOutfits->execute();
$outfitResults = $getOutfits->get_result();
if($outfitResults->num_rows != 0){
  echo"<div class='columns is-multiline'>";
  foreach($outfitResults as $outfits){
?>
<div class="column is-3">
  <div class="box is-centered">
      <img width="70" src="<?=$outfits["image"]?>"><br>
    <p class="truncate has-text-white"><?=filter(htmlentities($outfits["name"]))?></p>
    <button onclick="outfit(<?=$outfits["id"]?>)" class="button is-success is-fullwidth mb-3">Equip</button>
    <button onclick="deleteOutfit(<?=$outfits["id"]?>)" class="button is-danger is-fullwidth">Delete</button>
  </div></div>
<?php }echo"</div>";}else{ print("<p class='has-text-white'>You do not have any outfits saved.</p>"); } ?>