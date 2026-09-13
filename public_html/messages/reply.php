<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");

if(!isset($_SESSION['id'])) {
    die(header("Location: /login"));
}

if(isset($_POST['body']) && isset($_POST['topic'])) {
    if(!is_numeric($_POST['topic'])) {
        die("not numeric");
    }

    $getForumThread = $conn->prepare("SELECT * FROM `msgs_brz` WHERE `id`=?");
    $getForumThread->bind_param("i", $_POST["topic"]);
    $getForumThread->execute();
    $get_topic = $getForumThread->get_result();

    if(mysqli_num_rows($get_topic) == 0) {
        die("not found");
    }
    
    if($user['flood'] > time()){
        die("You are sending messages too fast");
    }

    $me = $get_topic->fetch_assoc();
    if(strlen($_POST['body']) < 7 || strlen($_POST['body']) > 400) {
        die("Body must be between 7-400 characters");
    }
    
    $title = "RE: ".$me['title']."";
    $insert = $conn->prepare("INSERT INTO `msgs_brz` (`id`, `title`, `body`, `sender`, `reciever`, `time`) VALUES(NULL,?,?,?,?,?)");
    $insert->bind_param("ssiii", $title, $_POST["body"], $user['id'], $me['sender'], $time);
    $insert->execute();
    $stmt = $conn->prepare("UPDATE `brz_users` SET `flood`=? WHERE `id`=?");
    $stmt->bind_param("ii", $newFlood, $user['id']);
    $stmt->execute();
    die("success");
}