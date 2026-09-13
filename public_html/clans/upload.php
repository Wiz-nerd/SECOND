<?php
$errors = array();
if(isset($_POST['name']) && isset($_POST['bio'])){
  if(empty($_POST['name']) || empty($_POST['bio'])){
    $errors[] = "All fields are required";
  }else{

    if (!file_exists($_FILES['img']['tmp_name']) || !is_uploaded_file($_FILES['img']['tmp_name'])) {
      $errors[] = "A clan image is required";
    }else{

      if(strlen($_POST['name']) < 4 || strlen($_POST['name'] > 30)){
        $errors[] = "Name must be between 4-30 characters";
      }else{

        if($user['flood'] > time()){
          $errors[] = "You are creating clans too fast";	
        }

        if(strlen($_POST['bio']) < 7 || strlen($_POST['bio'] > 1000)){
          $errors[] = "Description must be between 7-1000 characters";
        }else{

          if($user['shards'] < 50){
            $errors[] = "You do not have enough shards to create a clan";
          }else{

            $ext1 = pathinfo($_FILES['img']['name']);
            $ext = $ext1['extension'];
            if($ext != "png"){
              $errors[] = "Icon must have the .png extension";
            }else{

              $size = filesize($_FILES['img']['tmp_name']);
              if($size > 3145728){ // 3MB limit
                $errors[] = "Icon must not exceed 3 megabytes";
              }else{

                $headshot = "/assets/images/shop/pending.png";
                if($user["membership"] != 0){
                  $newShards = $user['shards'] - 25;
                }else{
                  $newShards = $user['shards'] - 50;
                }
                $stmt = $conn->prepare("UPDATE `brz_users` SET `flood`=?,`shards`=? WHERE `id`=?");
                $stmt->bind_param("iii", $newFlood, $newShards, $user['id']);
                $stmt->execute();
                $stmt2 = $conn->prepare("INSERT INTO `brz_clans` (`id`, `name`, `bio`, `icon`, `owner`) VALUES(NULL,?,?,?,?)") or die(mysqli_error($conn));
                $stmt2->bind_param("sssi", $_POST['name'], $_POST['bio'], $headshot, $user['id']) or die(mysqli_error($conn));
                $stmt2->execute() or die(mysqli_error($conn));
                $target_dir = "../assets/images/clans/".$conn->insert_id.".png";
                move_uploaded_file($_FILES['img']['tmp_name'], $target_dir);
                die(header("Location: /clans/view/$conn->insert_id"));

              }
            }
          }
        }
      }
    }
  }
}
?>