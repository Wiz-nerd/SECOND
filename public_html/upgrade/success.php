<?php
$pageName = "Success";
require("../config/param.php");
require("../config/header.php");
if(!isset($_SESSION['id'])){
  header("Location: /login/");
}
?>
<div class="section">
  <div class="container">
    <div class="box">
      <h1 class="title has-text-white">Thank you for your purchase</h1>
      <p class="has-text-white">Your goods will be granted to your account shortly. If they did not arrive within 24 hours, contact Canada immediatly. This purchase will help us pay our staff, servers, and domain for <?=$site[0]?>. Thank you.</p><br>
    </div>
  </div>
</div>
<?=footer();?>
