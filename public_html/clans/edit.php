<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if($user['id'] != 1){ die(); }
$getClan = $conn->prepare("SELECT * FROM `brz_clans` WHERE `id`=? AND `approved`=?");
$getClan->bind_param("ii", $_GET['id'], $i);
$getClan->execute();
$clanResult = $getClan->get_result();

if(!isset($_GET['id']) || $_GET['id'] == null || $clanResult->num_rows == 0 || !is_numeric($_GET['id'])){
	header("Location: /error/code/404");
}

$clan = $clanResult->fetch_assoc();
if($clan['owner'] != $user['id']){
	header("Location: /error/code/404");
}
$pageName = "Edit ".htmlentities($clan['name'])."";

$getRanks = $conn->prepare("SELECT * FROM `brz_clan_ranks` WHERE `clan_id`=?");
$getRanks->bind_param("i", $_GET['id']);
$getRanks->execute();
$rankResult = $getRanks->get_result();

$getDRank = $conn->prepare("SELECT * FROM `brz_clan_ranks` WHERE `clan_id`=? AND `default`=?");
$getDRank->bind_param("ii", $_GET['id'], $i);
$getDRank->execute();
$rankDResult = $getDRank->get_result();
$defaultRank = $rankDResult->fetch_assoc();

include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
?>
<section class="section">
  <div class="container">
    <div class="content">
      <text class="title has-text-white">Edit <?=filter(htmlentities($clan['name']))?></text><br>
      <p class="subtitle has-text-danger has-text-weight-semibold mb-3" id="error"></p>
      <div class="columns">
        <div class="column is-7">
          <div class="box">
            <div class="columns">
              <div class="column is-6">
                <img width="70%" height="70%" src="<?=$clan['icon']?>" class="image is-centered">
                <div style="height:10px;"></div>
                <div id="file-js-example" class="file is-dark has-name is-fullwidth">
                  <label class="file-label">
                    <input class="file-input" type="file" id="img">
                    <span class="file-cta">
                      <span class="file-icon">
                        <i class="fas fa-upload"></i>
                      </span>
                      <span class="file-label">
                        Update clan thumbnail
                      </span>
                    </span>
                    <span class="file-name has-text-white is-border-black">
                      No file selected
                    </span>
                  </label>
                </div>
              </div>
              <div class="column is-5">
                <textarea style="height:100%;" class="textarea is-dark is-fullheight" id="desc"><?=filter(htmlentities($clan['bio']))?></textarea>
              </div>
            </div>
            <hr>
            <button onclick="window.history.back()" class="button is-black">Return to clan</button>
            <button id="save" class="button is-success">Save Changes</button>
          </div>
        </div>
        <div class="column is-5">
          <div class="box">
            <label class="label has-text-white">Manage clan members</label>
            <div class="select is-dark is-fullwidth mb-3">
              <select id="rank">
                <?php foreach($rankResult as $ranks){ ?>
                <option value="<?=$ranks['id']?>"><?=$ranks['name']?></option>
                <?php } ?>
              </select>
            </div>
            <div id="members"></div>
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
    formData.append('id', <?=$clan['id']?>)
    formData.append('desc', $("#desc").val())
    formData.append('img', $("#img")[0].files[0])
    jQuery.ajax({
      type: "POST",
      cache: false,
      url: "/clans/editBack",
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
        window.location='/clans/view/'+resp+''
      }
    }).fail(function() {
      alert("There was a problem handling your request.")
    })
  })

  $("#rank").change(function(){
    $.get("/clans/editMembers.php?id="+this.value+"", function(data, status){
     $("#members").html(data) 
    })
  })
</script>