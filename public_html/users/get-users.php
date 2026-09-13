<?php
include($_SERVER['DOCUMENT_ROOT'] . "/config/param.php");
if(!isset($_GET['page'])) {
  $page = 1;
} else {
  $page = $_GET['page'];
}
if(!is_numeric($page)) {
  die();
}

$offset = ($page - 1) * 12;
if($offset < 0) { $offset = 0; }

if(!isset($_GET['search'])){
  $getUsers = $conn->prepare("SELECT * FROM `brz_users` ORDER BY `last_online` DESC LIMIT 12 OFFSET $offset");
  $uN = $conn->query("SELECT * FROM `brz_users`")->num_rows;
  $pages = ceil($uN / 12);
}else{
  $search = mysqli_real_escape_string($conn, $_GET['search']);
  $e = "%".$search."%";
  $getUsers = $conn->prepare("SELECT * FROM `brz_users` WHERE `username` LIKE ? ORDER BY `last_online` DESC LIMIT 12 OFFSET $offset");
  $getUsers->bind_param("s", $e);
  $userSearchPages = $conn->prepare("SELECT * FROM `brz_users` WHERE `username` LIKE ?");
  $userSearchPages->bind_param("s", $e);
  $userSearchPages->execute();
  $userPages = $userSearchPages->get_result()->num_rows;
  $pages = ceil($userPages / 12);
}
$getUsers->execute();
$userResult = $getUsers->get_result();
if($userResult->num_rows > 0){
  echo"<div class='columns is-multiline'>";
  foreach($userResult as $users){
    if($users['last_online'] + 180 > time()){
      $color = "green";
    }else{
      $color = '#A9A9A9';
    }
?>
<div class="column is-3">
  <div class="box is-centered">
    <a href="/profile/<?=$users['username']?>">
      <img src="<?=$users['avatar_img']?>"><br>
      <text class="has-text-white is-centered truncate"><?=$users['username']?> <span style="color:<?=$color?>;">●</span></text>
    </a>
  </div>
</div>
<?php } ?>
</div>
<div style="display: none;" class="pagination is-centered is-mobile">
  <?php if(intval($page)!=1){ ?>
  <a class='button is-success' onclick='getPage(<?=strval(intval($page) - 1)?>, "<?=$_GET['search']?>")'>Previous Page</a>
  <?php
                             }else{ ?>
  <a class='button is-success' disabled="true">Previous Page</a>
  <?php
  }
  ?>
  <p class='has-text-white'>&nbsp; Page <?=$page?> of <?=$pages?>&nbsp;</p>
  <a class='button is-success' <?=($page + 1 > $pages) ? "disabled='disabled'" : "onclick='getPage(".strval(intval($page) + 1).", &quot;".isset($_GET['search'])."&quot;)'"?>>Next Page</a>
  <br><br></div>
<?php
  }else{
  die("<p class='has-text-white'>There are no results.</p>");
}
?>