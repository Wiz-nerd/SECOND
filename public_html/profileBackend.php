<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(isset($_GET['username']) && ($_GET['username'] != null)){
  $username = mysqli_real_escape_string($conn, $_GET['username']);
  $profile = userInfo2($_GET['username']);
}else{
  $profile = userInfo2($user['username']);
}
$profils = userInfoNumU($profile['username']);
if(mysqli_num_rows($profils) > 0 && (mysqli_num_rows($profils) != null)){
}else{
  header("Location: /error/code/404");
}
if($profile['last_online'] + 180 > time()){
  $color = "lime";
}else{
  $color = '#A9A9A9';
}

$numbers = ['1', '2', '3', '4', '5'];
$random = array_rand($numbers, 2);
if($numbers[$random[0]] == 1){
  $conn->query("UPDATE `brz_users` SET `profile_views`= `profile_views` + '1' WHERE `id`='$profile[id]'");
}

//friending
if(isset($_POST['user'])){
  $id = $_POST['user'];
  $uid = intval($id);
  if(!$uid){
    die("Is not a numeric id");
  }
  $getPrevFriend = $conn->prepare("SELECT * FROM `friends` WHERE ((sender=? AND reciever=?) OR (sender=? AND reciever=?)) AND (status=? OR status=?)") or die("1:".$conn->error);
  $getPrevFriend->bind_param("iiiiii", $user['id'], $id, $id, $user['id'], $i, $o) or die("2:".$conn->error);
  $getPrevFriend->execute() or die("a:".$conn->error);
  if($getPrevFriend->get_result()->num_rows > 0){
    die("You already sent this person a friend request");
  }
  $insertFriend = $conn->prepare("INSERT INTO `friends` (`id`, `sender`, `reciever`, `status`) VALUES(NULL, ?, ?, ?)") or die("3:".$conn->error);
  $insertFriend->bind_param("iii", $user['id'], $id, $o);
  $insertFriend->execute() or die($conn->error);
  die("success");
}

//unfriending
if(isset($_POST['unfriend'])){
  $id = $_POST['unfriend'];
  $uid = intval($id);
  if(!$uid){
    die("Is not a numeric id");
  }
  $getPrevFriend = $conn->prepare("SELECT * FROM `friends` WHERE ((sender=? AND reciever=?) OR (sender=? AND reciever=?)) AND (status=?)") or die("1:".$conn->error);
  $getPrevFriend->bind_param("iiiii", $user['id'], $id, $id, $user['id'], $i) or die("2:".$conn->error);
  $getPrevFriend->execute() or die("a:".$conn->error);
  if($getPrevFriend->get_result()->num_rows == 0){
    die("You were never their friend in the first place");
  }
  $insertFriend = $conn->prepare("DELETE FROM `friends` WHERE ((sender=? AND reciever=?) OR (sender=? AND reciever=?))") or die("3:".$conn->error);
  $insertFriend->bind_param("iiii", $user['id'], $id, $id, $user['id']);
  $insertFriend->execute() or die($conn->error);
  die("success");
}

//queries
$getFriend1 = $conn->prepare("SELECT * FROM `friends` WHERE `reciever`=? AND `sender`=?");
$getFriend1->bind_param("ii", $user['id'], $profile['id']);
$getFriend1->execute();
$friend1 = $getFriend1->get_result();
$getFriend2 = $conn->prepare("SELECT * FROM `friends` WHERE `sender`=? AND `reciever`=?");
$getFriend2->bind_param("ii", $user['id'], $profile['id']);
$getFriend2->execute();
$friend2 = $getFriend2->get_result();
if($friend1->num_rows == 0 && $friend2->num_rows == 0){ // not added
  $friend = 1;
} else if($friend1->num_rows == 1){

  $frienderer = $friend1->fetch_assoc();

  if($frienderer['status'] == 0){
    $friend = 3;
  } else {
    $friend = 2;
  }

} else if($friend2->num_rows == 1) {

  $frienderer = $friend2->fetch_assoc();

  if($frienderer['status'] == 0){
    $friend = 3;
  } else {
    $friend = 2;
  }
} else {
  $friend = null;
}

$getFriends = $conn->prepare("SELECT * FROM friends WHERE (sender=? OR reciever=?) AND status=1 ORDER BY id DESC LIMIT 8");
$getFriends->bind_param("ii", $profile['id'], $profile['id']);
$getFriends->execute();
$friends = $getFriends->get_result();
$getFriends2 = $conn->prepare("SELECT * FROM friends WHERE (sender=? OR reciever=?) AND status=1");
$getFriends2->bind_param("ii", $profile['id'], $profile['id']);
$getFriends2->execute();
$friends2 = $getFriends2->get_result();
$count = $friends2->num_rows;