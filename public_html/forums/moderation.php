<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if($user['rank'] != 0){
if(isset($_GET['lock'])){
  $lock = mysqli_real_escape_string($conn, $_GET['lock']);
  $stmt = $conn->prepare("UPDATE `forum_threads` SET `locked`=? WHERE `id`=?");
  $stmt->bind_param("ii", $i, $lock);
  $stmt->execute();
  header("Location: /forums/thread/$lock");
}

if(isset($_GET['unlock'])){
  $unlock = mysqli_real_escape_string($conn, $_GET['unlock']);
  $stmt = $conn->prepare("UPDATE `forum_threads` SET `locked`=? WHERE `id`=?");
  $stmt->bind_param("ii", $o, $unlock);
  $stmt->execute();
  header("Location: /forums/thread/$unlock");
}


if(isset($_GET['delete'])){
  $delete = mysqli_real_escape_string($conn, $_GET['delete']);
  $stmt = $conn->prepare("UPDATE `forum_threads` SET `hidden`=? WHERE `id`=?");
  $stmt->bind_param("ii", $i, $delete);
  $stmt->execute();
  header("Location: /forums/thread/$delete");
}

if(isset($_GET['show'])){
  $show = mysqli_real_escape_string($conn, $_GET['show']);
  $stmt = $conn->prepare("UPDATE `forum_threads` SET `hidden`=? WHERE `id`=?");
  $stmt->bind_param("ii", $o, $show);
  $stmt->execute();
  header("Location: /forums/thread/$show");
}

if(isset($_GET['deleteReply'])){
  $newReply = "[ Content Removed ]";
  $deleteReply = mysqli_real_escape_string($conn, $_GET['deleteReply']);
  $stmt = $conn->prepare("UPDATE `forum_replies` SET `body`=? WHERE `id`=?");
  $stmt->bind_param("si", $newReply, $deleteReply);
  $stmt->execute();
  echo"<script>window.history.go(-1)</script>";
}
}else{
 die("You are not authorized to do this action"); 
}