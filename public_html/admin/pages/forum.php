<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$getPendingItems = $conn->prepare("SELECT * FROM `items_brz` WHERE `pending`=? ORDER BY `id` DESC");
$getPendingItems->bind_param("i", $i);
$getPendingItems->execute();
$itemResult = $getPendingItems->get_result();
$getPendingClans = $conn->prepare("SELECT * FROM `brz_clans` WHERE `approved`=? ORDER BY `id` DESC");
$getPendingClans->bind_param("i", $o);
$getPendingClans->execute();
$clanResult = $getPendingClans->get_result();
?>
<div class="content">
  <h2 class="title has-text-white">Approval</h2>
  <div class="columns is-multiline">
    <?php
    foreach($itemResult as $items){
      $creator = userInfo($items['creator']);
    ?>
    <div class="column is-3">
      <div class="box">
        <p class="subtitle has-text-white is-centered truncate"><?=htmlentities($items['item_name'])?></p>
        <p class="has-text-white is-centered truncate">By: <?=$creator['username']?></p>
        <img class="image is-centered" src="/assets/images/shop/avatar_images/<?=$items['id']?>.png"><br>
        <div class="is-centered">
          <button id="accept" value="<?=$items['id']?>" class="button is-success">Accept</button>
          <button id="decline" value="<?=$items['id']?>" class="button is-danger">Decline</button>
        </div>
      </div>
    </div>
    <script>
      var item = <?=$items['id']?>;
    </script>
      <?php
      }
      ?>
      <?php
      foreach($clanResult as $clans){
      ?>
      <div class="column is-3">
        <div class="box">
          <p class="subtitle has-text-white is-centered truncate"><?=htmlentities($clans['name'])?></p>
      <img class="image is-centered" src="/assets/images/clans/<?=$clans['id']?>.png"><br>
        <div class="is-centered">
          <button id="acceptClan" value="<?=$clans['id']?>" class="button is-success">Accept</button>
      <button id="declineClan" value="<?=$clans['id']?>" class="button is-danger">Decline</button>
      </div>
      </div>
      </div>
        <script>
      var clan = <?=$clans['id']?>;
    </script>
      <?php
        }
        ?>
      </div>
      </div>
      <script>
        //item approval
        $("#accept").click(function(){
        $.post("/admin/posts/approval.php", {
          request: "accept",
          id: $("#accept").val()
        }).done(function(resp){
          switch(resp){
            case "e1":
              alert("Item is already approved")
              break;
            case "e2":
              alert("Item is deleted")
              break;
            default:
              window.location='/avatar/render-testing/itemPreview?render='+item+''
          }
        }).fail(function(){
          alert("There was an error");
        });
      });
      //item decline
      $("#decline").click(function(){
        $.post("/admin/posts/approval.php", {
          request: "decline",
          id: $("#decline").val()
        }).done(function(resp){
          switch(resp){
            case "e1":
              alert("Item is already approved")
              break;
            case "e2":
              alert("Item is deleted")
              break;
            default:
              window.location.reload();
          }
        }).fail(function(){
          alert("There was an error");
        });
      });
      //clan approval
      $("#acceptClan").click(function(){
        $.post("/admin/posts/approval2.php", {
          request: "accept",
          id: $("#acceptClan").val()
        }).done(function(resp){
          switch(resp){
            case "e1":
              alert("Clan is already approved")
              break;
            case "e2":
              alert("Clan is deleted")
              break;
            default:
              alert(resp)
              window.location='/clans/view/'+clan+''
          }
        }).fail(function(){
          alert("There was an error");
        });
      });
      //clan decline
      $("#declineClan").click(function(){
        $.post("/admin/posts/approval2.php", {
          request: "decline",
          id: $("#declineClan").val()
        }).done(function(resp){
          switch(resp){
            case "e1":
              alert("Clan is already approved")
              break;
            case "e2":
              alert("Clan is deleted")
              break;
            default:
              alert(resp)
              window.location.reload();
          }
        }).fail(function(){
          alert("There was an error");
        });
      });
    </script>