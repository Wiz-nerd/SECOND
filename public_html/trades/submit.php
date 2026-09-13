<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(!isset($_SESSION['id'])){
  die(header("Location: /login/"));
}

$giving = mysqli_real_escape_string($conn,$_GET['give']);
$getting = mysqli_real_escape_string($conn,$_GET['get']);
$uid = mysqli_real_escape_string($conn,$_GET['u']);
$time = time();

mysqli_query($conn,"INSERT INTO `brz_trades_brz_orzo_brz` VALUES(NULL,'$user[id]','$uid','$giving','$getting','$time','p')");
echo"<script>window.alert('Sent Trade!');window.location='/profile/'</script>";
  
?>