<?php
include($_SERVER['DOCUMENT_ROOT'] . '/games/test/gameValidator.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset='utf-8'>
<meta name='viewport' content='width=device-width'>
<title><?=filter(htmlentities($game['title']))?></title> 
<link href='style.css' rel='stylesheet' type='text/css' />
<script src='https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
<script src='collision.js'></script>
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>
<body style='background-position: center;
background-repeat: round;
background-size: cover; background-image: url(<?=$game['skybox']?>);'>
<div class="chat">
<form method="post" id="send">
<input id="chat" class="form-control bg-dark text-white" placeholder="Chat here" minlength="1" maxlength="50">
</form><div style="height:10px"></div>
<div id="msgs"></div>
</div>
<div id='<?=$user['id']?>' class='player' style='text-align: center; background-image: url(<?=$user['avatar_img']?>);'>
<h2 style='text-align: center;color: #fff;text-shadow: rgb(0, 0, 0) 3px 0px 0px, rgb(0, 0, 0) 2.83487px 0.981584px 0px, rgb(0, 0, 0) 2.35766px 1.85511px 0px, rgb(0, 0, 0) 1.62091px 2.52441px 0px, rgb(0, 0, 0) 0.705713px 2.91581px 0px, rgb(0, 0, 0) -0.287171px 2.98622px 0px, rgb(0, 0, 0) -1.24844px 2.72789px 0px, rgb(0, 0, 0) -2.07227px 2.16926px 0px, rgb(0, 0, 0) -2.66798px 1.37182px 0px, rgb(0, 0, 0) -2.96998px 0.42336px 0px, rgb(0, 0, 0) -2.94502px -0.571704px 0px, rgb(0, 0, 0) -2.59586px -1.50383px 0px, rgb(0, 0, 0) -1.96093px -2.27041px 0px, rgb(0, 0, 0) -1.11013px -2.78704px 0px, rgb(0, 0, 0) -0.137119px -2.99686px 0px, rgb(0, 0, 0) 0.850987px -2.87677px 0px, rgb(0, 0, 0) 1.74541px -2.43999px 0px, rgb(0, 0, 0) 2.44769px -1.73459px 0px, rgb(0, 0, 0) 2.88051px -0.838247px 0px;'><?=$user['username']?></h2></div>
<div id='demo_obstacle' class='object' style="background-color:#<?=$game['baseplate_color']?>;"></div>
</body>
  <script>
  /*$(document).ready(function(){
   setInterval(function(){
     $.get("/games/test/getPlayers?id=<?=$game['id']?>&user=<?=$user['id']?>", function(res, status){
     	//alert(status);
     })
   }, 1000)
  })*/
  var pusher=new Pusher('3dc76a3eb80033edcef5',{cluster:'us2'});var channel=pusher.subscribe('my-channel');channel.bind('my-event',function(data){$("#msgs").prepend("<text class=\"text-white\" id=\"msg\">"+data.sender+": "+data.message+"</text><br>")});$(function(){$("#send").submit(function(event){event.preventDefault();$.post("/pusher/index.php",{msg:$("#chat").val()}).done(function(resp){$("#chat").val("")})})})
  </script>
<script>
class Platformer {
  constructor(player_start_pos) {
    this.start_pos = player_start_pos;
    this.acceleration = 0;
    /*$.post("/games/test/updatePos", {
      xPos: $("#<?=$user['id']?>").css("left"),
      yPos: $("#<?=$user['id']?>").css("top")
    }).done(function(resp){})*/
  }

  gravity(ammount_in_px) {
    $("#<?=$user['id']?>").css({
      "top": "+=" + (ammount_in_px + platformer.acceleration) + "px"
    });

    //To make the acceleration less, make this number less:
    platformer.acceleration += .01;
    /*$.post("/games/test/updatePos", {
      xPos: $("#<?=$user['id']?>").css("left"),
      yPos: $("#<?=$user['id']?>").css("top")
    }).done(function(resp){})*/
  }

  check_death() {
    if (remove_px($("#<?=$user['id']?>").css("top")) >= window.innerHeight) {
      return true;
    } else {
      return false;
    }
  }

  respawn() {
    //Brings you back to the start
    $("#<?=$user['id']?>").css({
      "top": "229.40px",
      "left": "80px"
    });
    /*$.post("/games/test/updatePos", {
      xPos: $("#<?=$user['id']?>").css("left"),
      yPos: $("#<?=$user['id']?>").css("top")
    }).done(function(resp){})*/
  }

  touching_platform() {
    //Check is the player is touching a platform
    for (var i = 0; i <= $(".object").length - 1; i++) {
      if (collision($("#<?=$user['id']?>")[0], $(".object")[i])) {
        return true;
      }
    }
    return false;
  }

  touching_lava() {
    //Check is the player is touching a platform
    for (var i = 0; i <= $(".lava").length - 1; i++) {
      if (collision($(".lava")[i], $("#<?=$user['id']?>")[0])) {
        return true;
        break;
      }
    }
    return false;
  }

  jump() {
    var jump = setInterval(function () {
      //If you want the jump to be stronger increase the number
      $("#<?=$user['id']?>").css({ "top": "-=5px" });
      /*$.post("/games/test/updatePos", {
        xPos: $("#<?=$user['id']?>").css("left"),
        yPos: $("#<?=$user['id']?>").css("top")
      }).done(function(resp){})*/
    });

    //To make the jump go higher increase the number here:
    setTimeout(function () { clearInterval(jump) }, 200);
  }

  left() {
    var left = setInterval(function () {
      //If you want going left to be stronger increase the number:
      $("#<?=$user['id']?>").css({ "left": "-=3px" });
      /*$.post("/games/test/updatePos", {
        xPos: $("#<?=$user['id']?>").css("left"),
        yPos: $("#<?=$user['id']?>").css("top")
      }).done(function(resp){})*/
    });

    setTimeout(function () { clearInterval(left) }, 10);
  }

  right() {
    var right = setInterval(function () {
      //If you want going right to be stronger increase the number:
      $("#<?=$user['id']?>").css({ "left": "+=3px" });
      /*$.post("/games/test/updatePos", {
        xPos: $("#<?=$user['id']?>").css("left"),
        yPos: $("#<?=$user['id']?>").css("top")
      }).done(function(resp){})*/
    });

    setTimeout(function () { clearInterval(right) }, 10);
  }

  check_key(keyCodes) {
    if (platformer.touching_platform()) {
      if (keyCodes[38]) {//spacebar keycode
        platformer.jump();
      }
    }
    if (keyCodes[39]) {
      platformer.right();
    }
    if (keyCodes[37]) {
      platformer.left();
    }
  }
}

var platformer = new Platformer({
  "y": (window.innerHeight / 2) - 100 + "px",
  "x": "30px"
});

window.onload = function () {
  //Teleport to the start point when the game loads:
  platformer.respawn();

  //You can remove lines 90-93 when you start. This was just for a demo:
  $("#demo_obstacle").css({
    "top": remove_px(platformer.start_pos["y"]) + 273 + "px",
    "left": platformer.start_pos["x"]
  });

  //Detecting the keys being pressed:
  var down = {};//Keys that currently being pressed
  $(document).keydown(function (e) {
    down[e.keyCode] = true;
    //console.log(e.keyCode);
  });
  $(document).keyup(function (e) {
    down[e.keyCode] = false;
  });
  //Checking if a certain key is pressed. If that key is pressed then the check_key method is called:
  //The number determines the speed of the left and right movements
  setInterval(function () { platformer.check_key(down) }, 35);
}


//Respawn if the player is below the screens view or it dies by an obstacle
setInterval(function () {
  if (platformer.check_death()) {
    platformer.respawn();
    /*$.post("/games/test/updatePos", {
      xPos: $("#<?=$user['id']?>").css("left"),
      yPos: $("#<?=$user['id']?>").css("top")
    }).done(function(resp){})*/
  }
});

//mimic gravity
setInterval(function () {
  if (platformer.touching_lava()) {
    platformer.respawn();
    /*$.post("/games/test/updatePos", {
      xPos: $("#<?=$user['id']?>").css("left"),
      yPos: $("#<?=$user['id']?>").css("top")
    }).done(function(resp){})*/
  }
  if (!platformer.touching_platform()) {
    //The number represents how strong the gravity is. The 1.5 means how much pixels per milisecond the game will make the player further from the top
    platformer.gravity(.8);
  } else {
    platformer.acceleration = 0;
  }
});
</script>
<style>
.form-control, .form-control:focus{
   box-shadow: 0 0 0 0 rgba(255, 255, 255, 0);
   border: rgba(255, 255, 255, 0);
}  
</style>