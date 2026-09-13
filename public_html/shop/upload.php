<?php
$errors = array();
if(isset($_POST['name']) || isset($_POST['desc'])){
if(empty($_POST['name']) || empty($_POST['desc'])){
$errors[] = "All fields are required";
}else{

if(strlen($_POST['name']) < 4 || strlen($_POST['name']) > 30){
$errors[] = "Name must be between 4-30 characters";
}else{

if($user['flood'] > time()){
$errors[] = "You are uploading items too fast";	
}

if(strlen($_POST['desc']) < 8 || strlen($_POST['desc']) > 200){
$errors[] = "Description must be between 7-200 characters";
}else{

if(!is_numeric($_POST['price'])){
$errors[] = "Price must be a number";
}else{

if($_POST['price'] < 0){
$errors[] = "Price must be above 0";
}else{
  
if(empty($_POST['price']) || $_POST['price'] == 0 || $_POST['price'] == null && $_POST['status'] == 'onsale'){
$status = "free";
}else if($_POST['status'] == 'offsale'){
$status = "offsale";
}else{
$status = "onsale";
}

$ext1 = pathinfo($_FILES['img']['name']);
$ext = $ext1['extension'];
if($ext != "png"){
$errors[] = "Image must have the .png extension";
}else{

$size = filesize($_FILES['img']['tmp_name']);
if($size > 3145728){ // 3MB limit
$errors[] = "Image must not exceed 3 megabytes";
}else{

if (!in_array($_POST['type'], ['shirt', 'pant'])) //fix by sesuiro B)
exit;

$headshot = "/assets/images/shop/pending.png";
$stmt = $conn->prepare("UPDATE `brz_users` SET `flood`=? WHERE `id`=?");
$stmt->bind_param("ii", $newFlood, $user['id']);
$stmt->execute();
$stmt = $conn->prepare("INSERT INTO `items_brz` (`id`, `item_name`, `item_body`, `item_price`, `creator`, `headshot`, `type`, `status`, `time`) VALUES(NULL,?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssiisssi", $_POST['name'], $_POST['desc'], $_POST['price'], $user['id'], $headshot, $_POST['type'], $status, $time);
$stmt->execute();
$target_dir = "../assets/images/shop/avatar_images/".$conn->insert_id.".png";
move_uploaded_file($_FILES['img']['tmp_name'], $target_dir);
die("Asset has been created and is pending moderator approval.");

}
}
}
}
}
}
}
}
?>