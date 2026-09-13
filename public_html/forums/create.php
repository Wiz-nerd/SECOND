<?php
require($_SERVER['DOCUMENT_ROOT']."/config/param.php");

if(isset($_SESSION['id'])){
$userCheck = $conn->prepare("SELECT * FROM `brz_users` WHERE `id`=?");
$userCheck->bind_param("i", $_SESSION['id']);
$userCheck->execute();
$UCheck = $userCheck->get_result();
if(mysqli_num_rows($UCheck) < 1){
session_destroy();
header("Location: /index.php");
}
$user = $UCheck->fetch_assoc();
}

if(!isset($_SESSION['id'])) {
die(header("Location: /login"));
}

if(isset($_POST["title"]) && isset($_POST["body"]) && isset($_POST["topic"])) {
if(!is_numeric($_POST["topic"])) {
die("not numeric");
}

$getSubForums = $conn->prepare("SELECT * FROM `forum_tables` WHERE `id`=?");
$getSubForums->bind_param("i", $_POST["topic"]);
$getSubForums->execute();
$get_topic = $getSubForums->get_result();

if(mysqli_num_rows($get_topic) == 0) {
die("not found");
}

if($user['flood'] > time()){
    die("You are posting threads too fast");
}

if(strlen($_POST["title"]) < 3 || strlen($_POST["title"]) > 40) {
die("Title must be 3-40 characters long");
} else if(strlen($_POST["body"]) < 3 || strlen($_POST["body"]) > 2000) {
die("Body must be 3-2000 characters long");
}

$insert = $conn->prepare("INSERT INTO `forum_threads` VALUES(NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
$insert->bind_param("sisiiiiii", $_POST["title"], $time, $_POST["body"], $user['id'], $time, $_POST['topic'], $o, $o, $o);
$insert->execute();

$updatePCount = $conn->prepare("UPDATE `brz_users` SET `posts`=? WHERE `id`=?");
$newP = $user['posts'] + 1;
$updatePCount->bind_param("ii", $newP, $user['id']);
$updatePCount->execute();
$stmt = $conn->prepare("UPDATE `brz_users` SET `flood`=? WHERE `id`=?");
$stmt->bind_param("ii", $newFlood, $user['id']);
$stmt->execute();
die("success");
}