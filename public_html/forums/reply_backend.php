<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");

if(!isset($_SESSION['id'])) {
    die(header("Location: /login"));
}

if(isset($_POST['body']) && isset($_POST['topic'])) {
    if(!is_numeric($_POST['topic'])) {
        die("not numeric");
    }

    $getForumThread = $conn->prepare("SELECT * FROM `forum_threads` WHERE `id`=?");
    $getForumThread->bind_param("i", $_POST["topic"]);
    $getForumThread->execute();
    $get_topic = $getForumThread->get_result();

    if(mysqli_num_rows($get_topic) == 0) {
        die("not found");
    }

    if(strlen($_POST['body']) < 3 || strlen($_POST['body']) > 2000) {
        die("Body must be 3-2000 characters long");
    }
    
    if($user['flood'] > time()){
    die("You are posting threads too fast");
    }

    $insert = $conn->prepare("INSERT INTO `forum_replies` VALUES(NULL, ?, ?, ?, ?)");
    $insert->bind_param("siii", $_POST["body"], $time, $user['id'], $_POST['topic']);
    $insert->execute();
    $insert = $conn->prepare("UPDATE `forum_threads` SET `updated`=? WHERE `id`=?");
    $insert->bind_param("ii", $time, $_POST['topic']);
    $insert->execute();

    $updatePCount = $conn->prepare("UPDATE `brz_users` SET `posts`=? WHERE `id`=?");
    $newP = $user['posts'] + 1;
    $updatePCount->bind_param("ii", $newP, $user['id']);
    $updatePCount->execute();
    $stmt = $conn->prepare("UPDATE `brz_users` SET `flood`=? WHERE `id`=?");
    $stmt->bind_param("ii", $newFlood, $user['id']);
    $stmt->execute();
    die("success");
}