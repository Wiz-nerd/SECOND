<?php
$pageName = "Canceled";
require("../config/param.php");
require("../config/header.php");
if(!isset($_SESSION['id'])){
  header("Location: /login/");
}
?>
<div class="section">
  <div class="container">
    <div class="box">
      <h1 class="title has-text-white">The purchase was canceled</h1>
      <p class="has-text-white">You have not been charged.</p><br>
    </div>
  </div>
</div>
<?=footer();?>
