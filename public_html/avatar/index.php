<?php
$pageName = "Edit Avatar";
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
include($_SERVER['DOCUMENT_ROOT'] . "/config/header.php");

if(!isset($_SESSION['id'])){
  die(header("Location: /login/"));
}

$colorsToHex = [
  "#FF0000" => "red",
  "#FFB57C" => "beige",
  "#0040FF" => "blue",
  "#FF6A00" => "orange",
  "#FFFF00" => "yellow",
  "#111111" => "black",
  "#824629" => "brown",
  "#FFFFFF" => "white"
];

$types = [
  "Hats" => "hat",
  "Faces" => "face",
  "Tools" => "tool",
  "Shirts" => "shirt",
  "Pants" => "pant"
];

$getUserAvatar = $conn->prepare("SELECT * FROM `brz_avatar` WHERE `user_id`=?");
$getUserAvatar->bind_param("i", $user['id']);
$getUserAvatar->execute();
$avatarResult = $getUserAvatar->get_result();
$avatar = $avatarResult->fetch_assoc();

function colorToHex($limb){
  global $colorsToHex;

  $color = array_search($limb, $colorsToHex);
  return $color;
}

?>
<div class="modal" id="outfit"><div onclick="closeModal2()" class="modal-background"></div><div class="modal-card"><header class="modal-card-head"><p class="modal-card-title has-text-white">Save Current Outfit</p><button class="delete" onclick="closeModal()2" aria-label="close"></button></header><section class="modal-card-body"><center></center><p class="has-text-danger has-text-weight-bold is-centered" id="err"></p><p class="has-text-white has-text-weight-bold mb-3">Outfit Name</p><input id="name" placeholder="My Outfit" class="input is-dark mb-3"><button id="save" class="button is-success">Save Outfit</button></section><footer class="modal-card-foot"></footer></div></div>
<section class="section">
  <div class="container">
    <div class="columns">
      <div class="column is-3">
        <div class="box">
          <div id="avatar" class="is-centered"></div>
          <div style="height:10px"></div>
          <div class="is-centered">
            <button onclick="saveOutfit()" class="button is-info is-small">Save Outfit</button>
            <button onclick="redraw()" class="button is-success is-small">Redraw</button>
            <button onclick="reset()" class="button is-danger is-small">Reset</button>
          </div>
        </div>
        <div class="box">
          <p class="subtitle has-text-white">Body Colors</p>
          <div style="padding-top:20px;min-height:260.5px;width:200px;margin:auto;">
            <div id="body-colors"></div>
          </div>
        </div>
      </div>
      <div class="column is-8">
        <div class="box">
          <div class="tabs is-centered is-small"><ul>
            <?php foreach($types as $name => $link){ ?>
            <li><a onclick="getPage('<?=$link?>', 0)"><?=$name?></a></li>
            <?php } ?>
            <li><a onclick="getOutfits()">Outfits</a></li>
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
  $(function(){
    $("#avatar").load("/avatar/render-testing/get-avatar.php");
    $("#inventory").load("/avatar/render-testing/get-inv.php?sort=hat&page=undefined");
    $("#wearing").load("/avatar/render-testing/wearing.php");
    $("#body-colors").load("/avatar/render-testing/get-colors.php");
  })

  function getPage(type, page) {
    $("#inventory").load("/avatar/render-testing/get-inv.php?sort="+type+"&page="+page);
  };

  function getOutfits(){
    $.get("/avatar/render-testing/getOutfits.php", function(data, status){
      $("#inventory").html(data)
    })
  }

  function redraw(){
    $("#avatar").html("<img src='/assets/images/loading.gif'>");
    $.post("/avatar/renderer/", {
      request: "redraw"
    }).done(function(resp){
      if(resp == "success"){
        $("#avatar").load("/avatar/render-testing/get-avatar.php");
      }else{
        alert(resp) 
      }
    }).fail(function(){
      alert("An unexpected error occured");
    })

    $("#wearing").load("/avatar/render-testing/wearing.php");
  }

  function wear(id){
    $("#avatar").html("<img src='/assets/images/loading.gif'>");
    $("button[name="+id+"]").prop("disabled", true)
    $.post("/avatar/renderer/", {
      request: "render",
      id: id
    }).done(function(resp){
      if(resp == "success"){
        $("#avatar").load("/avatar/render-testing/get-avatar.php");
      }else{
        alert(resp) 
      }
    }).fail(function(){
      alert("An unexpected error occured");
    })
    redraw();
    $("#wearing").load("/avatar/render-testing/wearing.php");
  }

  function saveOutfit(){
    const modal = $("#outfit")
    modal.addClass("is-active")
  }

  $("#save").click(function(){
    $.post("/avatar/render-testing/saveOutfit.php", {
      name: $("#name").val()
    }).done(function(resp){
      if(resp != "succ"){
        $("#err").text(resp) 
      }else{
        closeModal()
        $.get("/avatar/render-testing/getOutfits.php", function(data, status){
          $("#inventory").html(data)
        })
      }
    })
  })

  function deleteOutfit(id){
    $.post("/avatar/render-testing/saveOutfit.php", {
      id: id,
      delete: "delete"
    }).done(function(resp){
      if(resp != "succ"){
        $("#err2").text(resp) 
      }else{
        closeModal()
        $("#err2").empty()
        $.get("/avatar/render-testing/getOutfits.php", function(data, status){
          $("#inventory").html(data)
        })
      }
    })
  }

  function outfit(id){
    $("#avatar").html("<img src='/assets/images/loading.gif'>");
    $("button[name="+id+"]").prop("disabled", true)
    $.post("/avatar/renderer/", {
      request: "outfit",
      id: id
    }).done(function(resp){
      if(resp == "success"){
        $("#avatar").load("/avatar/render-testing/get-avatar.php");
      }else{
        alert(resp) 
      }
    }).fail(function(){
      alert("An unexpected error occured");
    })
    redraw();
    $("#wearing").load("/avatar/render-testing/wearing.php");
  }

  function remove(id){
    $("#avatar").html("<img src='/assets/images/loading.gif'>");
    $.post("/avatar/renderer/", {
      request: "remove",
      id: id
    }).done(function(resp){
      if(resp == "success"){
        $("#avatar").load("/avatar/render-testing/get-avatar.php");
      }else{
        alert(resp) 
      }
    }).fail(function(){
      alert("An unexpected error occured");
    })
    redraw();
    $("#wearing").load("/avatar/render-testing/wearing.php");
  }

  function reset(){
    $("#avatar").html("<img src='/assets/images/loading.gif'>");
    $.post("/avatar/renderer/", {
      request: "reset",
    }).done(function(resp){
      if(resp == "success"){
        $("#avatar").load("/avatar/render-testing/get-avatar.php");
        $("#body-colors").load("/avatar/render-testing/get-colors.php");
      }else{
        alert(resp) 
      }
    }).fail(function(){
      alert("An unexpected error occured");
    })
    redraw();
    $("#wearing").load("/avatar/render-testing/wearing.php");
  }

  function openColorModal(limb){
    switch(limb){
      case "headColor":
        var text = ["Change Head Color", "head"];
        $(".section").append('<div class="modal is-active"><div onclick="closeModal()" class="modal-background"></div><div class="modal-card"><header class="modal-card-head"><p class="modal-card-title has-text-white">'+text[0]+'</p><button class="delete" onclick="closeModal()" aria-label="close"></button></header><section class="modal-card-body"><center><?php foreach($colorsToHex as $color => $colors){?><div onclick="changeColor(\'<?=$colors?>\', \''+text[1]+'\')" style="height:50px;width:50px;background:<?=$color?>;margin-left:5px;display:inline-block;"></div> <?php } ?></center></section><footer class="modal-card-foot"></footer></div></div>');
        break;
      case "torsoColor":
        var text = ["Change Torso Color", "torso"];
        $(".section").append('<div class="modal is-active"><div onclick="closeModal()" class="modal-background"></div><div class="modal-card"><header class="modal-card-head"><p class="modal-card-title has-text-white">'+text[0]+'</p><button class="delete" onclick="closeModal()" aria-label="close"></button></header><section class="modal-card-body"><center><?php foreach($colorsToHex as $color => $colors){?><div onclick="changeColor(\'<?=$colors?>\', \''+text[1]+'\')" style="height:50px;width:50px;background:<?=$color?>;margin-left:5px;display:inline-block;"></div> <?php } ?></center></section><footer class="modal-card-foot"></footer></div></div>');
        break;
      case "rightArmColor":
        var text = ["Change Right Arm Color", "right_arm"];
        $(".section").append('<div class="modal is-active"><div onclick="closeModal()" class="modal-background"></div><div class="modal-card"><header class="modal-card-head"><p class="modal-card-title has-text-white">'+text[0]+'</p><button class="delete" onclick="closeModal()" aria-label="close"></button></header><section class="modal-card-body"><center><?php foreach($colorsToHex as $color => $colors){?><div onclick="changeColor(\'<?=$colors?>\', \''+text[1]+'\')" style="height:50px;width:50px;background:<?=$color?>;margin-left:5px;display:inline-block;"></div> <?php } ?></center></section><footer class="modal-card-foot"></footer></div></div>');
        break;
      case "leftArmColor":
        var text = ["Change Left Arm Color", "left_arm"];
        $(".section").append('<div class="modal is-active"><div onclick="closeModal()" class="modal-background"></div><div class="modal-card"><header class="modal-card-head"><p class="modal-card-title has-text-white">'+text[0]+'</p><button class="delete" onclick="closeModal()" aria-label="close"></button></header><section class="modal-card-body"><center><?php foreach($colorsToHex as $color => $colors){?><div onclick="changeColor(\'<?=$colors?>\', \''+text[1]+'\')" style="height:50px;width:50px;background:<?=$color?>;margin-left:5px;display:inline-block;"></div> <?php } ?></center></section><footer class="modal-card-foot"></footer></div></div>');
        break;
      case "rightLegColor":
        var text = ["Change Right Leg Color", "right_leg"];
        $(".section").append('<div class="modal is-active"><div onclick="closeModal()" class="modal-background"></div><div class="modal-card"><header class="modal-card-head"><p class="modal-card-title has-text-white">'+text[0]+'</p><button class="delete" onclick="closeModal()" aria-label="close"></button></header><section class="modal-card-body"><center><?php foreach($colorsToHex as $color => $colors){?><div onclick="changeColor(\'<?=$colors?>\', \''+text[1]+'\')" style="height:50px;width:50px;background:<?=$color?>;margin-left:5px;display:inline-block;"></div> <?php } ?></center></section><footer class="modal-card-foot"></footer></div></div>');
        break;
      case "leftLegColor":
        var text = ["Change Left Leg Color", "left_leg"];
        $(".section").append('<div class="modal is-active"><div onclick="closeModal()" class="modal-background"></div><div class="modal-card"><header class="modal-card-head"><p class="modal-card-title has-text-white">'+text[0]+'</p><button class="delete" onclick="closeModal()" aria-label="close"></button></header><section class="modal-card-body"><center><?php foreach($colorsToHex as $color => $colors){?><div onclick="changeColor(\'<?=$colors?>\', \''+text[1]+'\')" style="height:50px;width:50px;background:<?=$color?>;margin-left:5px;display:inline-block;"></div> <?php } ?></center></section><footer class="modal-card-foot"></footer></div></div>');
        break;
      default:
        alert("Invalid limb");
    }
  }

  function changeColor(color, limb){
    $("#avatar").html("<img src='/assets/images/loading.gif'>");
    $.post("/avatar/renderer/", {
      request: "color",
      color: color,
      limb: limb
    }).done(function(resp){
      if(resp != "success"){
        alert(resp)
      }else{
        $("#avatar").load("/avatar/render-testing/get-avatar.php");
        $("#body-colors").load("/avatar/render-testing/get-colors.php");
      }
    }).fail(function(){
      alert("An unexpected error occured")
    })
    redraw();
  }

  function closeModal2() {
    var modal = $('#outfit');
    modal.removeClass("is-active");
  }
  
  function closeModal() {
    var modal = $('.modal');
    modal.removeClass("is-active");
  }
</script>
<?=footer();?>