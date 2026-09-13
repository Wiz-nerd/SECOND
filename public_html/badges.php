<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
$pageName = "Badges";
include($_SERVER['DOCUMENT_ROOT'] . "/config/header.php");

$getBadges = $conn->prepare("SELECT * FROM `brz_badges` ORDER BY `id` ASC");
$getBadges->execute();
$badgeResults = $getBadges->get_result();
?>
<section class="section">
  <div class="container">
    <div class="content">
      <?php foreach($badgeResults as $badges){ ?>
      <div class="box">
        <div class="columns is-multiline">
          <div class="column is-3">
            <img width="250" src="<?=$badges['icon']?>" alt="<?=$badges['name']?>">
          </div>
          <div class="column is-6">
            <h1 class="title has-text-white"><?=$badges['name']?></h1>
            <span class="has-text-white"><?=$badges['desc']?></span>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>
  </div>
</section>
<?=footer()?>