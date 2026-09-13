<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');

if(isset($_POST['name']) || isset($_POST['desc'])){

if(empty($_POST['name']) || empty($_POST['desc'])){
	die("All fields are required");
}

if(strlen($_POST['name']) < 4 || strlen($_POST['name']) > 30){
	die("Name must be between 4-30 characters");
}

if($user['flood'] > time()){
	die("You are uploading items too fast");	
}

if(strlen($_POST['desc']) < 8 || strlen($_POST['desc']) > 200){
	die("Description must be between 7-200 characters");
}

if(!is_numeric($_POST['price'])){
	die("Price must be a number");
}

if(empty($_POST['price']) && $_POST['status'] == 'onsale'){
$status = "free";
}else if(($_POST['status'] == 'offsale') || ($_POST['status'] == 'offsale' && empty($_POST['price']))){
$status = "offsale";
}else{
$status = "onsale";
}

$ext1 = pathinfo($_FILES['img']['name']);

$ext = $ext1['extension'];
if($ext != "png"){
	die("Image must have the .png extension");
}

$size = filesize($_FILES['img']['tmp_name']);

if($size > 3145728){
	die("Image must not exceed 3 megabytes");
}

if (!in_array($_POST['type'], ['hat', 'face', 'tool'])){
	die("You can only upload hats, faces, or tools");
}

$headshot = "/assets/images/shop/pending.png";
$stmt = $conn->prepare("UPDATE `brz_users` SET `flood`=? WHERE `id`=?");
$stmt->bind_param("ii", $newFlood, $user['id']);
$stmt->execute();
$stmt = $conn->prepare("INSERT INTO `items_brz` (`id`, `item_name`, `item_body`, `item_price`, `creator`, `headshot`, `type`, `status`, `time`) VALUES(NULL,?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssiisssi", $_POST['name'], $_POST['desc'], $_POST['price'], $i, $headshot, $_POST['type'], $status, $time);
$stmt->execute();
$target_dir = "../../assets/images/shop/avatar_images/".$conn->insert_id.".png";
move_uploaded_file($_FILES['img']['tmp_name'], $target_dir);

die("succ");

}
?>