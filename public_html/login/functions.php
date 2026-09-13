<?php
$errors = array();
if(isset($_POST['uname']) && isset($_POST['pwd'])){
  $uname = mysqli_real_escape_string($conn,$_POST['uname']);
  $pass = mysqli_real_escape_string($conn,$_POST['pwd']);
  $usernameCheck = $conn->prepare("SELECT * FROM `brz_users` WHERE `username`=?");
  $usernameCheck->bind_param("s", $uname);
  $usernameCheck->execute();
  $UCheck = $usernameCheck->get_result();
  if(mysqli_num_rows($UCheck) < 1){
  $errors[] = 'User does not exist!';
  }else{
  $user = $UCheck->fetch_assoc();
  $newPass = password_hash($pass, PASSWORD_DEFAULT);
  if(!password_verify($pass, $user['passsword'])){
		$errors[] = 'Incorrect Password!';
 }else{
  $_SESSION['id'] = $user['id'];
  $ip = $_SERVER['REMOTE_ADDR'];
  $stmt = $conn->prepare("INSERT INTO `brzlogins` VALUES(NULL,?,?,?,?,?)");
  

  die("Logged in, click 'home' to continue!");
             }
           }
         }
?>