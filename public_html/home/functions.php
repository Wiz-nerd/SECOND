<?php
$errors = array();
$succ = array();
$time = time();
function GetNews(){
// Below is the connection variable to load blog posts onto the feed
$conn2 = mysqli_connect('localhost','id21046293_bdb','Toddlogan08!','id21046293_bdb');
if(!$conn2){
  die("<center>The blog is currently unavailable, we are sorry for the inconvinience.</center>"); 
}
$yes = "yes";
$getPosts = $conn2->prepare("SELECT * FROM `blog_posts` WHERE `display`=? ORDER BY `id` DESC LIMIT 5");
$getPosts->bind_param("s", $yes);
$getPosts->execute();
$postResults = $getPosts->get_result();

foreach($postResults as $posts){ 
?>
  <a href="https://blog.brickorzo.com/post?id=<?=$posts['id']?>"><p class="subtitle has-text-white truncate"><?=$posts['title']?></p></a><br>
<?php } 
}
	if(isset($_POST['status'])){
    $status = $_POST['status'];
    $s = strlen($status);
    if($s < 3 || $s > 100){
    	$errors[] = 'Status must be between 3-100 characters';
    }else{
  if($user['flood'] > time()){
  	$errors[] = 'You are updating your status too fast!';
    }else{
    	$stmt = $conn->prepare("INSERT INTO `statuses` (`id`, `status`, `poster_id`, `time`) VALUES(NULL,?,?,?)");
    	$stmt->bind_param("sii", $status, $user['id'], $time);
    	$stmt->execute();
    	$stmt = $conn->prepare("UPDATE `brz_users` SET `status`=? WHERE `id`=?");
    	$stmt->bind_param("si", $status, $user['id']);
    	$stmt->execute();
      $stmt = $conn->prepare("UPDATE `brz_users` SET `flood`=? WHERE `id`=?");
      $stmt->bind_param("ii", $newFlood, $user['id']);
      $stmt->execute();
    	$succ[] = 'Successfully updated status!';
       }
    }
}

$getStatuses = $conn->prepare("SELECT * FROM `statuses` ORDER BY `id` DESC LIMIT 10");
$getStatuses->execute();
$Statuses = $getStatuses->get_result();
?>