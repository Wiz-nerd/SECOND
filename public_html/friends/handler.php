<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(isset($_POST['friend'])){
switch($_POST['request']){
case "accept":
$findReal = $conn->prepare("SELECT * FROM `friends` WHERE `id`=? AND `reciever`=? AND `status`=?") or die(mysqli_error($conn));
$findReal->bind_param("iii", $_POST['friend'], $_SESSION['id'], $o) or die(mysqli_error($conn));
$findReal->execute() or die(mysqli_error($conn));
$friendRes = $findReal->get_result() or die(mysqli_error($conn));
if($friendRes->num_rows == 0){
die();
}
$updateFriend = $conn->prepare("UPDATE `friends` SET `status`=? WHERE `id`=?") or die(mysqli_error($conn));
$updateFriend->bind_param("ii", $i, $_POST['friend']) or die(mysqli_error($conn));
$updateFriend->execute() or die(mysqli_error($conn));
$stmt = $conn->prepare("UPDATE `brz_users` SET `flood`=? WHERE `id`=?");
$stmt->bind_param("ii", $newFlood, $user['id']);
$stmt->execute();
die("succ");
break;


case "remove":
$findReal = $conn->prepare("SELECT * FROM `friends` WHERE `id`=? AND (`sender`=? OR `reciever`=?) AND `status`=?") or die(mysqli_error($conn));
$findReal->bind_param("iiii", $_POST['friend'], $_SESSION['id'], $_SESSION['id'], $i) or die(mysqli_error($conn));
$findReal->execute() or die(mysqli_error($conn));
$friendRes = $findReal->get_result();
if($friendRes->num_rows < 0){
die("NOOO");
}
$updateFriend = $conn->prepare("DELETE FROM `friends` WHERE `id`=?") or die(mysqli_error($conn));
$updateFriend->bind_param("i", $_POST['friend']) or die(mysqli_error($conn));
$updateFriend->execute() or die(mysqli_error($conn));
$stmt = $conn->prepare("UPDATE `brz_users` SET `flood`=? WHERE `id`=?");
$stmt->bind_param("ii", $newFlood, $user['id']);
$stmt->execute();
die("succ");
break;
}
}