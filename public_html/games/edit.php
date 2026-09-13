<?php
include('../config/param.php');

$checkIfGameExists = $conn->prepare("SELECT * FROM `brz_games` WHERE `id`=?");
$checkIfGameExists->bind_param("i", $_GET['id']);
$checkIfGameExists->execute();
$gameCheckResults = $checkIfGameExists->get_result();

if(!isset($_GET['id']) || $_GET['id'] == null || $gameCheckResults->num_rows == 0){
  die(header("Location: /error/code/404")); 
}

$game = $gameCheckResults->fetch_assoc();
$creator = userInfo($game['creator']);

$getPlayerCount = $conn->prepare("SELECT * FROM `brz_game_joins` WHERE `game_id`=?");
$getPlayerCount->bind_param("i", $game['id']);
$getPlayerCount->execute();
$playerCount = $getPlayerCount->get_result()->num_rows;

$pageName = "Edit " . filter(htmlentities($game['title']));
include('../config/header.php');

if($user['id'] != $creator['id']){
  die(header("Location: /error/code/404")); 
}

if($game['status'] == "deleted"){
  die(header("Location: /error/code/404")); 
}

?>
<section class="section">
  <div class="container">
    <div class="content">
      <text class="title has-text-white">Edit <?=filter(htmlentities($game['title']))?></text><br>
      <p class="subtitle has-text-danger has-text-weight-semibold mb-3" id="error"></p>
      <div class="columns">
        <div class="column is-8">
          <div class="box">
            <div class="columns">
              <div class="column is-6">
                <img width="100%" height="100%" src="<?=$game['thumbnail']?>" class="image">
                <div style="height:10px;"></div>
                <div id="file-js-example" class="file is-dark has-name is-fullwidth">
                  <label class="file-label">
                    <input class="file-input" type="file" id="img">
                    <span class="file-cta">
                      <span class="file-icon">
                        <i class="fas fa-upload"></i>
                      </span>
                      <span class="file-label">
                        Update game thumbnail
                      </span>
                    </span>
                    <span class="file-name has-text-white is-border-black">
                      No file selected
                    </span>
                  </label>
                </div>
              </div>
              <div class="column is-5">
                <textarea style="height:100%;" class="textarea is-dark is-fullheight" placeholder="<?=filter(htmlentities($game['description']))?>" id="desc"><?=filter(htmlentities($game['description']))?></textarea>
              </div>
            </div>
            <hr>
            <button onclick="window.history.back()" class="button is-black">Return to game</button>
            <button id="save" class="button is-success">Save Changes</button>
          </div>
        </div>
        <div class="column is-4">
          <div class="box">
            <label class="label has-text-white">Update Game Visibility</label>
            <div class="select is-dark is-fullwidth mb-3">
              <select id="visible">
                <?php if($game['status'] == "public"): ?>
                <option value="public">Public</option>
                <option value="private">Private</option>
                <?php elseif($game['status'] == "private"): ?>
                <option value="private">Private</option>
                <option value="public">Public</option>
                <?php endif; ?>
              </select>
            </div>
            <label class="label has-text-white">Update Baseplate Color (Example: F0F0F0)</label>
            <input id="color" class="input is-dark mb-3" value="<?=$game['baseplate_color']?>">
            <label class="label has-text-white">Update Skybox (Must be an image link)</label>
            <input id="sky" class="input is-dark mb-3" value="<?=$game['skybox']?>">
            <button id="save2" class="button is-success">Save Changes</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?=footer()?>
<script>
  const fileInput = document.querySelector('#file-js-example input[type=file]');
  fileInput.onchange = () => {
    if (fileInput.files.length > 0) {
      const fileName = document.querySelector('#file-js-example .file-name');
      fileName.textContent = fileInput.files[0].name;
    }
  }

  $("#save").click(function(){
    $("#save").attr("disabled", true)
    var formData = new FormData()
    formData.append('id', <?=$game['id']?>)
    formData.append('desc', $("#desc").val())
    formData.append('img', $("#img")[0].files[0])
    jQuery.ajax({
      type: "POST",
      cache: false,
      url: "/games/editBack",
      data: formData,
      enctype: 'multipart/form-data',
      processData: false,
      contentType: false
    }).done(function(resp) {
      if(resp.indexOf("err") !== -1) {
        $("#error").text(resp)
        setTimeout(function(){
          $("#save").attr("disabled", false);
          $("#error").empty()
        }, 2000)
      }else{
        window.location='/games/view/'+resp+''
      }
    }).fail(function() {
      alert("There was a problem handling your request.")
    })
  })

  $("#save2").click(function(){
    $.post("/games/editBack", {
      id: <?=$game['id']?>,
      status: $("#visible").val(),
      color: $("#color").val(),
      sky: $("#sky").val()
  }).done(function(resp){
    if(resp.indexOf("err") !== -1) {
      $("#error").text(resp)
      setTimeout(function(){
        $("#save").attr("disabled", false);
        $("#error").empty()
      }, 2000)
    }else{
      window.location='/games/view/'+resp+''
    }
  }).fail(function() {
    alert("There was a problem handling your request.")
  })
  })
</script>