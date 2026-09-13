<?php
$pageName = "Create Game";
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
include($_SERVER['DOCUMENT_ROOT'] . '/games/uploadGame.php');
if(!isset($_SESSION['id'])){
  die(header("Location: /login/"));
}
?>
<div class="container">
  <div class="content">
    <div style="margin:auto;" class="column is-8">
      <?php foreach($errors as $error){ ?>
      <div class="notification is-danger"><?=$error?></div>
      <?php } ?>
      <div class="box">
        <p class="title has-text-white">Create Game</p>
        <form method="post" enctype="multipart/form-data">
          <input name="name" class="input is-dark" placeholder="Game Name">
          <div style="height:10px;"></div>
          <textarea name="bio" class="textarea is-dark" placeholder="Game Description"></textarea>
          <div style="height:10px;"></div>
          <div id="file-js-example" class="file is-dark has-name">
            <label class="file-label">
              <input class="file-input" type="file" name="img">
              <span class="file-cta">
                <span class="file-icon">
                  <i class="fas fa-upload"></i>
                </span>
                <span class="file-label">
                  Upload game thumbnail
                </span>
              </span>
              <span class="file-name has-text-white is-border-black">
                No file selected
              </span>
            </label>
          </div>
          <div style="height:10px;"></div>
          <button class="button is-success" type="submit">Publish game</button>
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

  if ( window.history.replaceState ) {
    window.history.replaceState( null, null, window.location.href );
  }
</script>