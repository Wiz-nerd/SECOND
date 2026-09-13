<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');

if(!isset($_SESSION['id'])){
	die(header("Location: /login/"));
}

if(isset($_POST['reciever']) && isset($_POST['title']) && isset($_POST['body'])){

if(empty($_POST['reciever']) || empty($_POST['title']) || empty($_POST['body'])){
    die("There are missing fields");
}

$p1 = $_POST['reciever'];
$p2 = $_POST['title'];
$p3 = $_POST['body'];

if(strlen($p2) < 3 || strlen($p2) > 40){
	die("Title must be between 3-40 characters");
}

if($user['flood'] > time()){
    die("You are sending messages too fast");
}

if(strlen($p3) < 7 || strlen($p3) > 400){
	die("Body must be between 7-400 characters");
}

if(!isset($_POST['reciever']) || $_POST['reciever'] == null || mysqli_num_rows(userInfoNumI($_POST['reciever'])) == 0 || $_POST['reciever'] == $user['id']){
	die("This user does not exist");
}

$stmt = $conn->prepare("INSERT INTO `msgs_brz` (`id`, `title`, `body`, `sender`, `reciever`, `time`) VALUES(NULL,?,?,?,?,?)");
$stmt->bind_param("ssiii", $p2, $p3, $user['id'], $p1, $time);
$stmt->execute();
$stmt = $conn->prepare("UPDATE `brz_users` SET `flood`=? WHERE `id`=?");
$stmt->bind_param("ii", $newFlood, $user['id']);
$stmt->execute();
die("Successfully sent message!");
}
?>