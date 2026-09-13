<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
if(!isset($_SESSION['id'])){
die(header("Location: /login/"));
}
?>
<h1 class="title has-text-white is-centered">Coming Soon</h1>