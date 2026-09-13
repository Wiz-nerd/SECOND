<?php
$errors = array();
if(isset($_POST['name']) && isset($_POST['bio'])){
  if(empty($_POST['name']) || empty($_POST['bio'])){
    $errors[] = "All fields are required";
  }else{

    if (!file_exists($_FILES['img']['tmp_name']) || !is_uploaded_file($_FILES['img']['tmp_name'])) {
      $errors[] = "A game thumbnail is required";
    }else{

      list($width, $height) = getimagesize($_FILES['img']['tmp_name']);
      if(($width != 800) && ($height != 480)){
        $errors[] = "Your thumbnail must be 800x480 pixels";
      }else{

        if(strlen($_POST['name']) < 4 || strlen($_POST['name'] > 30)){
          $errors[] = "Name must be between 4-30 characters";
        }else{

          if($user['flood'] > time()){
            $errors[] = "You are creating games too fast";	
          }

          if(strlen($_POST['bio']) < 7 || strlen($_POST['bio'] > 1000)){
            $errors[] = "Description must be between 7-1000 characters";
          }else{

            $ext1 = pathinfo($_FILES['img']['name']);
            $ext = $ext1['extension'];
            if($ext != "png"){
              $errors[] = "Thumbnail must have the .png extension";
            }else{

              $size = filesize($_FILES['img']['tmp_name']);
              if($size > 3145728){ // 3MB limit
                $errors[] = "Thumbnail must not exceed 3 megabytes";
              }else{

                $stmt = $conn->prepare("UPDATE `brz_users` SET `flood`=? WHERE `id`=?");
                $stmt->bind_param("ii", $newFlood, $user['id']);
                $stmt->execute();
                $target_dir = "/assets/images/games/".rand(0,9999999).".png";
                move_uploaded_file($_FILES['img']['tmp_name'], "../".$target_dir."");
                $status = "private";
                $stmt2 = $conn->prepare("INSERT INTO `brz_games` (`id`, `title`, `description`, `status`, `time`, `thumbnail`, `creator`) VALUES(NULL,?,?,?,?,?,?)") or die(mysqli_error($conn));
                $stmt2->bind_param("sssisi", $_POST['name'], $_POST['bio'], $status, $time, $target_dir, $user['id']) or die(mysqli_error($conn));
                $stmt2->execute() or die(mysqli_error($conn));
                die(header("Location: /games/view/$conn->insert_id"));

              }
            }
          }
        }
      }
    }
  }
}
?>