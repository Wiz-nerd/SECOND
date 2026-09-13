<?php
session_start();
require($_SERVER['DOCUMENT_ROOT'] . '/config/vendor/autoload.php');
$conn = mysqli_connect('localhost','','','');
$userCheck = $conn->prepare("SELECT * FROM `brz_users` WHERE `id`=?");
$userCheck->bind_param("i", $_SESSION['id']);
$userCheck->execute();
$UCheck = $userCheck->get_result();

if(mysqli_num_rows($UCheck) == 0){
  session_start();
  session_destroy();
  unset($_SESSION['id']);
  unset($_SESSION['admin']);
  header("Location: /index.php");
}

$user = $UCheck->fetch_assoc();

if(isset($_POST['msg'])){
  $options = array(
    'cluster' => 'us2',
    'useTLS' => true
  );

  $pusher = new Pusher\Pusher(
    '3dc76a3eb80033edcef5',
    'b595ffc3115e4f02006a',
    '1412704',
    $options
  );
  
  if($_POST['msg'] == "/b test"){
    $data['message'] = "Command successfully executed!<script>alert(\"Hello World!\")</script>";
  }else{
    $data['message'] = htmlentities($_POST['msg']);
  }
  
  $data['sender'] = $user['username'];
  $pusher->trigger('my-channel', 'my-event', $data);
}

?>