<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$pageName = "Credits";
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
?>
<section class="section">
  <div class="container">
    <div class="content">
      <div class="box">
        <h1 class="has-text-white">Credits</h1>
        <p class="has-text-white">Everyone deserves credit when completing good things for the <?=$site[0]?> community and to show love and support for them, so we have made this special credits page which can be viewed on the footer! This will list them in certain categories, now, enjoy watching all of the fantastic contributors of <?=$site[0]?>!</p>
        <hr>
        <h3 class="has-text-white subtitle">Website Developement</h3>
        <ul class="has-text-white">
          <!--<li>superJP335 (Majority of old site and new website)</li>-->
          <li>Sh4dyy (New website)</li>
          <li>Spooky (Old website)</li>
        </ul>
        <h3 class="has-text-white subtitle">Website & Discord Staff</h3>
        <ul class="has-text-white">
          <!--<li>superJP335 (Former)</li>-->
          <li>Sh4dyy (Current)</li>
          <li>Deadly (Current)</li>
        </ul>
        <p class="has-text-white">More will be added in the future as time goes on, thank you for reading!</p>
      </div>
    </div>
  </div>
</section>
<?=footer()?>