<?php
header("Content-type: json");
include("{$_SERVER['DOCUMENT_ROOT']}/config/param.php");
if(isset($_GET['username'])){
  if(userInfoNumU($_GET['username'])->num_rows == 0){
    $info = [
      "response" => "404"
    ];
    echo json_encode($info);
  }else{
    $u = userInfo2($_GET['username']);
    $info = [
      "response" => "success",
      "id" => $u['id'],
      "username" => $u['username'],
      "status" => filter($u['status']),
      "bio" => filter($u['bio']),
      "avatar" => $u['avatar_img'],
      "forum_posts" => $u['posts'],
      "rank" => $u['rank'],
      "profile_views" => $u['profile_views'],
      "join_date" => $u['join_date'],
      "last_online" => $u['last_online']
    ];
    echo json_encode($info);
  }
}
?>