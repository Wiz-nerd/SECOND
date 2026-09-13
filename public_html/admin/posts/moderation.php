<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(isset($_POST['reset']) && isset($_POST['user'])){

	$player = userInfoNumI($_POST['user']);
	$player2 = $player->fetch_assoc();

	if($user['rank'] == 0){
		die("You are not an administrator");
	}

	if($player->num_rows == 0){
		die("This user does not exist");
	}

	if($player2['rank'] != 0){
		die("You cannot reset usernames of administrators");
	}


	function generateRandomString($length = 12) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	$newUsername = generateRandomString();
	$stmt = $conn->prepare("UPDATE `brz_users` SET `username`=? WHERE `id`=?");
	$stmt->bind_param("si", $newUsername, $_POST['user']);
	$stmt->execute();
	die("succ");
}

if(isset($_POST['reason']) && isset($_POST['length'])){

	$player = userInfoNumI($_POST['user']);
	$player2 = $player->fetch_assoc();

	if($user['rank'] == 0){
		die("You are not an administrator");
	}

	if($player->num_rows == 0){
		die("This user does not exist");
	}

	if($player2['rank'] != 0){
		die("You cannot ban administrators");
	}

	if(strlen($_POST['reason']) < 1){
     	die("A ban reason is required"); 
    }
    
    $getBans = $conn->query("SELECT * FROM `bans` WHERE `user`='$player2[id]'");
    if(mysqli_num_rows($getBans) != 0){
     	die("This user is already banned"); 
    }
  
    if($_POST['length'] == "term"){
     $length = 1;
     $term = 1;
    }else{
     $length = $_POST['length'] + $time;
     $term = 0;
    }
       
	$stmt = $conn->prepare("INSERT INTO `bans` VALUES (NULL,?,?,?,?,?,?,?)") or die($conn->error);
	$stmt->bind_param("iisiiii", $player2['id'], $user['id'], $_POST['reason'], $length, $time, $o, $term) or die($conn->error);
	$stmt->execute() or die($conn->error);
	die("succ");
}
?>