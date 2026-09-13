<?php
$pageName = "Create Clan";
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
include($_SERVER['DOCUMENT_ROOT'] . '/clans/upload.php');
if(!isset($_SESSION['id'])){
  die(header("Location: /login/"));
}

//die("<h1 class='title is-centered has-text-white'>Disabled for maintenance</h1>");
?>
<div class="container">
  <div class="content">
    <div style="margin:auto;" class="column is-8">
      <?php foreach($errors as $error){ ?>
      <div class="notification is-danger">
        <?=$error?>
      </div>
      <?php } ?>
      <div class="box">
        <p class="title has-text-white">Create Clan</p>
        <form method="post" enctype="multipart/form-data">
          <input name="name" class="input is-dark" placeholder="Clan Name">
          <div style="height:10px;"></div>
          <textarea name="bio" class="textarea is-dark" placeholder="Clan Description"></textarea>
          <div style="height:10px;"></div>
          <div id="file-js-example" class="file is-dark has-name">
            <label class="file-label">
              <input class="file-input" type="file" name="img">
              <span class="file-cta">
                <span class="file-icon">
                  <i class="fas fa-upload"></i>
                </span>
                <span class="file-label">
                  Upload Clan Icon
                </span>
              </span>
              <span class="file-name has-text-white is-border-black">
                No file selected
              </span>
            </label>
          </div>
          <div style="height:10px;"></div>
          <?php if($user["membership"] != 0){ $texts = ["Because you have Plus, the clan creation fee is now 50% cheaper.","Create clan for 25 shards"]; }else{ $texts = ["","Create clan for 50 shards"]; } ?>
          <p class="help has-text-white"><?=$texts[0]?></p>
          <button class="button is-success" type="submit"><?=$texts[1]?></button>
        </form>
      </div>
    </div>
  </div>
</div>
<?=footer()?>
<script>
  const fileInput = document.querySelector('#file-js-example input[type=file]');
  fileInput.onchange = () => {
    if (fileInput.files.length > 0) {
      const fileName = document.querySelector('#file-js-example .file-name');
      fileName.textContent = fileInput.files[0].name;
    }
  }
</script>