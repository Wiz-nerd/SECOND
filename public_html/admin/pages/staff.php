<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$getUserCount = $conn->prepare("SELECT * FROM `brz_users`");
$getUserCount->execute();
$userResult = $getUserCount->get_result();
?>
<h1 style="color: white;">Sorry, this is coming soon!</h1>