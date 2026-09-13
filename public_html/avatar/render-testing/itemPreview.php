<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
if(isset($_GET['render'])){
  $id = mysqli_real_escape_string($conn, $_GET['render']);
  $itemGet = GetItem($id);
  if(mysqli_num_rows($itemGet) < 1){
    die();
  }
  
  $item = $itemGet->fetch_assoc();

  switch($item['type']){
    case 'hat':
      $pos = 50;
      break;
    case 'face':
      $pos = 60;
      $avatar = "/avatar/render-testing/sesuiri.png";
      break;
    case 'shirt':
      $pos = 150;
      break;
    case 'pant':
      $pos = 152;
      break;
    case 'tool':
      $pos = 120;
      break;
    default:
      die();
  }

  $avatar_img = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . "$avatar");
  $hat = imagecreatefrompng($_SERVER['DOCUMENT_ROOT'] . "/assets/images/shop/avatar_images/$item[id].png");
  $size = min(imagesx($avatar_img), imagesy($avatar_img));

  $im2 = imagecrop($avatar_img, ['x' => 0, 'y' => $pos, 'width' => $size, 'height' => $size]);
  $hat2 = imagecrop($hat, ['x' => 0, 'y' => $pos, 'width' => $size, 'height' => $size]);

  imagesavealpha($im2, true);
  imagesavealpha($hat2, true);

  imagecopy($im2, $hat2, 0, 0, 0, 0, 500, 500);
  $newImg = hash('ripemd160', $item['id']);
  imagepng($im2, "../../assets/images/shop/thumbnails/$newImg.png");
  $item_dir = "/assets/images/shop/thumbnails/$newImg.png";

  $stmt = $conn->prepare("UPDATE `items_brz` SET `headshot`=? WHERE `id`=?");
  $stmt->bind_param("si", $item_dir, $item['id']);
  $stmt->execute();
  header("Location: /shop/item/$id");
}
?>