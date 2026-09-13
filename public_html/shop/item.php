<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
include($_SERVER['DOCUMENT_ROOT'] . '/shop/itemBackend.php');
if(!isset($_GET['id']) || empty($_GET['id']) || mysqli_num_rows(GetItem($_GET['id'])) == null){
  header("Location: /error/code/404");
}
$item = GetItem($_GET['id']);
$it = $item->fetch_assoc();
$pageName = htmlentities($it['item_name']);
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
$creator = userInfo($it['creator']);
switch($it['status']){
  case 'onsale':
    $price = "Purchase for $it[item_price] Shards";
    break;
  case 'offsale':
    $price = "Offsale";
    break;
  case 'free':
    $price = "Purchase for free";
    break;
  case 'limited':
    $price = "Purchase for $it[item_price] Shards";
    break;
  default:
    $price = "???";
}

switch($it['type']){
  case 'hat':
    $type = "Hat";
    break;
  case 'shirt':
    $type = "Shirt";
    break;

  case 'tool':
    $type = "Tool";
    break;

  case 'pant':
    $type = "Pants";
    break;

  case 'face':
    $type = "Face";
    break;
  default:
    $type = "Unknown";
}

if($it['status'] == "limited"){ $stock = '<p class="has-text-danger is-centered"><b>'.$it['stock_left'].'/'.$it['stock'].' remaining</b></p>'; }else{ $stock = ''; }

if($it['is_crate'] == "yes"){
  $getCrateItems = $conn->prepare("SELECT * FROM `brz_crates` WHERE `item_id`=?");
  $getCrateItems->bind_param("i", $it['id']);
  $getCrateItems->execute();
  $crateResults = $getCrateItems->get_result();
  $crateVal = $crateResults->fetch_assoc();

  $getCrates = $conn->prepare("SELECT * FROM `brz_inv` WHERE `item`=? AND `buyer`=?");
  $getCrates->bind_param("ii", $crateVal['item_id'], $user['id']);
  $getCrates->execute();
  $crateR = $getCrates->get_result();
}
?>
<meta name="keywords" content="brickorzo, brickorzo game, brickorzo thread, brickorzo user, brickorzo item">
<meta name="author" content="Brickorzo">
<meta name="publisher" content="Brickorzo">
<meta name="theme-color" content="#fe8447">
<meta name="description" content="<?=htmlentities($it['item_body'])?>">
<meta property="og:image" content="<?=$it['headshot']?>">
<meta name="page-topic" content="Video Games">
<section class="section">
  <div class="container">
    <div class="content">
      <text class="title has-text-white"><?=htmlentities($it['item_name'])?> | </text>
      <text class="subtitle has-text-white">Created by <a href="/profile/<?=$creator['username']?>"><?=$creator['username']?></a></text>
      <br><br>
      <div class="columns">
        <div class="column is-3">
          <div class="box">
            <center>
              <img height="250" width="250" style="margin:auto;" src="<?=$it['headshot']?>">
            </center>
            <div style="height:10px;"></div>
            <?php if(isset($_SESSION['id']) && $user['rank'] != 0): ?>
            <button style="white-space:break-spaces;" onclick="window.location='/avatar/render-testing/itemPreview.php?render=<?=$it['id']?>'" class="button is-danger is-fullwidth mb-3">Rerender item</button>
            <?php endif; ?>
            <form method="post" id="e" style="margin-block-end: 0;">
              <?=$stock?>
              <button style="white-space:break-spaces;" id="buy" class="button is-success is-fullwidth mb-2"><?=$price?></button>
            </form>
            <?php if($it['is_crate'] == "yes"){ ?>
              <?php if($crateR->num_rows != 0): ?>
            <div class="select is-dark is-fullwidth mb-2">
              <select id="cid">
                <?php foreach($crateR as $crates): ?>
                <option value="<?=$crates['id']?>">Crate #<?=$crates['serial']?></option>
                <?php endforeach; ?>
              </select>
              </div>
            <button style="white-space:break-spaces;" id="crate" class="button is-info is-fullwidth">Open Crate</button>
              <?php endif; ?>
            <?php } ?>
            <p class="help is-danger" id="purchase_message"></p>
            <p class="help is-success" id="s"></p>
          </div>
          <div class="box">
            <q class="has-text-white"><?=nl2br(filter(htmlentities($it['item_body'])))?></q>
            <hr>
            <?php if($it['status'] == "limited"): ?>
            <text class="has-text-white"><b>RAP:</b> <?=calculateRAP($it['id'], false)?></text><br>
            <?php endif; ?>
            <text class="has-text-white"><b>Sales:</b> <?=getItemSales($it['id'])?></text><br>
            <text class="has-text-white"><b>Created:</b> <?=date("j/m/Y", $it['time'])?></text><br>
            <text class="has-text-white"><b>Type:</b> <?=$type?></text>
          </div>
        </div>
        <div class="column is-8">
          <div style="margin-left:-32px;margin-top:-15px;" class="tabs is-toggle is-centered is-fullwidth"><ul>
            <li><a onclick="tab(1)">Comments</a></li>
            <?php if($it['status'] == "limited"): ?>
            <li><a onclick="tab(2)">Resellers</a></li>
            <?php endif; ?>
            <?php if($it['is_crate'] == "yes"): ?>
            <li><a onclick="tab(4)">Crate Contents</a></li>
            <?php endif; ?>
            <li><a onclick="tab(3)">Recommended</a></li>
            </ul></div>
          <div id="extra">
            <div class="box">
              <h2 class="title has-text-white">Comments</h2>
              <p id="commentMsg" class="help is-danger"></p>
              <textarea class="textarea is-dark" placeholder="What are your thoughts on this item?" id="comment"></textarea>
              <div style="height:10px"></div>
              <button class="button is-success" id="submit">Post Comment</button>
              <hr>
              <?php
              $getItemComments = $conn->prepare("SELECT * FROM `item_comments` WHERE `item_id`=? ORDER BY `id` DESC LIMIT 5");
              $getItemComments->bind_param("i", $it['id']);
              $getItemComments->execute();
              $commentResults = $getItemComments->get_result();
              foreach($commentResults as $comments){
                $poster = userInfo($comments['user_id']);
              ?>
              <div class="columns">
                <div class="column is-2 column-is-vcentered">
                  <img style="width:65px;" src="<?=$poster['avatar_img']?>">
                </div>
                <div class="column is-8">
                  <div class="is-size-6">
                    <text class="has-text-white" style="cursor:initial;"><?=filter(htmlentities($comments['comment']))?></text></div>
                  <p class="has-text-white">Posted by <a href="/profile/<?=$poster['username']?>"><span><?=$poster['username']?></a> on <?=date("j/m/Y", $comments['time'])?></span></a></p>
            </div>
          </div>
          <?php
                }  
          ?>
        </div>
      </div>
    </div>
  </div>
</section>
<script>
  function tab(num){
    switch(num){
      case 1:
        $.get("/shop/pages/comments.php?id=<?=$it['id']?>", function(data, status){
          console.log(status)
          $("#extra").html(data)
        })
        break;
      case 2:
        $.get("/shop/pages/reselling.php?id=<?=$it['id']?>", function(data, status){
          console.log(status)
          $("#extra").html(data)
        })
        break;
      case 3:
        $.get("/shop/pages/recommended.php?id=<?=$it['id']?>", function(data, status){
          console.log(status)
          $("#extra").html(data)
        })
        break;
      case 4:
        $.get("/shop/pages/contents.php?id=<?=$it['id']?>", function(data, status){
          console.log(status)
          $("#extra").html(data)
        })
        break;
      default:
        $("#extra").html("<h1 class='has-text-white title'>Invalid Category</h1>")
        break;
    }
  }

  $("#e").submit(function(e){
    e.preventDefault();
    $.post("/shop/itemBackend", {
      buy: <?=$it['id']?>
    }).done(function(resp){
      if(resp != "Successfully bought item!") {
        $("#purchase_message").text(resp);
      } else {
        $('#buy').prop('disabled', true);
        $("#s").text(resp);
      };
    });
  });

  $("#submit").click(function(){
    $.post("/shop/itemBackend", {
      comment: $("#comment").val(),
      item: <?=$it['id']?>
    }).done(function(resp){
      if(resp != "succ"){
        $("#commentMsg").text(resp)
      }else{
        $("#submit").prop("disabled", true);
        window.location.reload();
      }
    })
  })

  $("#crate").click(function(){
    $.post("/shop/itemBackend", {
      crate: <?=$it['id']?>,
      serial: $("#cid").val(),
  }).done(function(resp){
    if(resp.indexOf("err") !== -1) {
      $("#purchase_message").text(resp)
    }else{
      $.getJSON("/api/shop/getItem", {
        id: resp
      }).done(function(json){
        $(".section").append('<div class="modal is-active"><div onclick="closeModal()" class="modal-background"></div><div class="modal-card"><header class="modal-card-head"><p class="modal-card-title has-text-white">Congratulations</p><button class="delete" onclick="closeModal()" aria-label="close"></button></header><section class="modal-card-body"><center><img src="'+json.headshot+'" class="image is-centered is-vcentered"></center><p class="has-text-white has-text-weight-bold is-centered mb-3">You have successfully rolled the '+json.name+'</p></section><footer class="modal-card-foot"></footer></div></div>');
      }).fail(function( jqxhr, textStatus, error ) {
        var err = textStatus + ", " + error;
        console.log( "Request Failed: " + err );
      });
    }
  });
  });

  function closeModal(){
    $(".modal").removeClass("is-active")
  }
</script>
<?=footer()?>