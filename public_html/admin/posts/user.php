<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(isset($_POST['search'])){

	$search = $_POST['search'];
	if(userInfoNumU($search)->num_rows == 0){
		die("User does not exist");
	}

	die("succ");
}