<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
if(isset($_POST['code'])){
	$code = $_POST['code'];

	if($user['rank'] == 0){
		die("You are not a Brickorzo employee");
	}

	if($code != $user['admin_code']){
		die("Incorrect passcode");
	}

	if(isset($_SESSION['admin'])){
		die("You are already logged in");
	}

	$_SESSION['admin'] = $user['admin_code'];
	die("succ");
}
?>

