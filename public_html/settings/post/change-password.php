<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(!isset($_SESSION['id'])){
	die(header("Location: /login/"));
}

if(isset($_POST['p1']) && isset($_POST['p2']) && isset($_POST['p3'])){
	if(empty($_POST['p1']) || empty($_POST['p2']) || empty($_POST['p3'])){
        die("There are missing fields");
	}
$p1 = $_POST['p1'];
$p2 = $_POST['p2'];
$p3 = $_POST['p3'];

if(!password_verify($p1, $user['passsword'])){
	die("Old password must match current password");
}

if(strlen($p2) < 6 || strlen($p2) > 200){
	die("New password must be between 6-200 characters");
}

if($p2 != $p3){
	die("Password confirmation does not match with new password");
}

$newPass = password_hash($p2, PASSWORD_DEFAULT);

$stmt = $conn->prepare("UPDATE `brz_users` SET `passsword`=? WHERE `id`=?");
$stmt->bind_param("si", $newPass, $user['id']);
$stmt->execute();

die("Successfully changed password!");
}
?>