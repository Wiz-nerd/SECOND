<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');

if(!isset($_SESSION['id'])){
  die(header("Location: /login/")); 
}

$checkIfGameExists = $conn->prepare("SELECT * FROM `brz_games` WHERE `id`=?");
$checkIfGameExists->bind_param("i", $_GET['id']);
$checkIfGameExists->execute();
$gameCheckResults = $checkIfGameExists->get_result();

$checkIfUserExists = $conn->prepare("SELECT * FROM `brz_game_joins` WHERE `user_id`=?");
$checkIfUserExists->bind_param("i", $_GET['user']);
$checkIfUserExists->execute();
$userCheckResults = $checkIfUserExists->get_result();

if((!isset($_GET['id']) || $_GET['id'] == null || $gameCheckResults->num_rows == 0) || (!isset($_GET['user']) || $_GET['user'] == null || $userCheckResults->num_rows == 0)){
  http_response_code(404);
  die(header("Location: /error/code/404")); 
}

$getPlayerPositions = $conn->prepare("SELECT * FROM `brz_player_positions` WHERE `game_id`=? AND `user_id`=?");
$getPlayerPositions->bind_param("ii", $_GET['id'], $_GET['user']);
$getPlayerPositions->execute();
$playerPositionsResults = $getPlayerPositions->get_result();

$pp = $playerPositionsResults->fetch_assoc();
$userInGame = $userCheckResults->fetch_assoc();
$userInfo = userInfo($userInGame['user_id']);
?>

<div id='<?=$userInfo['id']?>' class='player' style='text-align: center; background-image: url(<?=$userInfo['avatar_img']?>); top: <?=$pp['y_pos']?>; left: <?=$pp['x_pos']?>;'>
<h2 style='text-align: center;'><?=$userInfo['username']?></h2></div>
<script>
class Platformer {
  constructor(player_start_pos) {
    this.start_pos = player_start_pos;
    this.acceleration = 0;
    $.post("/games/test/updatePos", {
      xPos: $("#<?=$userInfo['id']?>").css("left"),
      yPos: $("#<?=$userInfo['id']?>").css("top")
    }).done(function(resp){})
  }

  gravity(ammount_in_px) {
    $("#<?=$userInfo['id']?>").css({
      "top": "+=" + (ammount_in_px + platformer.acceleration) + "px"
    });

    //To make the acceleration less, make this number less:
    platformer.acceleration += .01;
    $.post("/games/test/updatePos", {
      xPos: $("#<?=$userInfo['id']?>").css("left"),
      yPos: $("#<?=$userInfo['id']?>").css("top")
    }).done(function(resp){})
  }

  check_death() {
    if (remove_px($("#<?=$userInfo['id']?>").css("top")) >= window.innerHeight) {
      return true;
    } else {
      return false;
    }
  }

  respawn() {
    //Brings you back to the start
    /*$("#<?=$userInfo['id']?>").css({
      "top": "229.40px",
      "left": "80px"
    });
    $.post("/games/test/updatePos", {
      xPos: $("#<?=$userInfo['id']?>").css("left"),
      yPos: $("#<?=$userInfo['id']?>").css("top")
    }).done(function(resp){})*/
  }

  touching_platform() {
    //Check is the player is touching a platform
    for (var i = 0; i <= $(".object").length - 1; i++) {
      if (collision($("#<?=$userInfo['id']?>")[0], $(".object")[i])) {
        return true;
      }
    }
    return false;
  }

  touching_lava() {
    //Check is the player is touching a platform
    for (var i = 0; i <= $(".lava").length - 1; i++) {
      if (collision($(".lava")[i], $("#<?=$userInfo['id']?>")[0])) {
        return true;
        break;
      }
    }
    return false;
  }

  jump() {
    var jump = setInterval(function () {
      //If you want the jump to be stronger increase the number
      $("#<?=$userInfo['id']?>").css({ "top": "-=5px" });
      $.post("/games/test/updatePos", {
        xPos: $("#<?=$userInfo['id']?>").css("left"),
        yPos: $("#<?=$userInfo['id']?>").css("top")
      }).done(function(resp){})
    });

    //To make the jump go higher increase the number here:
    setTimeout(function () { clearInterval(jump) }, 200);
  }

  left() {
    var left = setInterval(function () {
      //If you want going left to be stronger increase the number:
      $("#<?=$user['id']?>").css({ "left": "-=4px" });
      $.post("/games/test/updatePos", {
        xPos: $("#<?=$userInfo['id']?>").css("left"),
        yPos: $("#<?=$userInfo['id']?>").css("top")
      }).done(function(resp){})
    });

    setTimeout(function () { clearInterval(left) }, 10);
  }

  right() {
    var right = setInterval(function () {
      //If you want going right to be stronger increase the number:
      $("#<?=$userInfo['id']?>").css({ "left": "+=4px" });
      $.post("/games/test/updatePos", {
        xPos: $("#<?=$userInfo['id']?>").css("left"),
        yPos: $("#<?=$userInfo['id']?>").css("top")
      }).done(function(resp){})
    });

    setTimeout(function () { clearInterval(right) }, 10);
  }

  check_key(keyCodes) {
    if (platformer.touching_platform()) {
      if (keyCodes[32] || keyCodes[38] || keyCodes[87]) {//spacebar keycode
        platformer.jump();
      }
    }
    if (keyCodes[39] || keyCodes[68]) {
      platformer.right();
    }
    if (keyCodes[37] || keyCodes[65]) {
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
    $.post("/games/test/updatePos", {
      xPos: $("#<?=$userInfo['id']?>").css("left"),
      yPos: $("#<?=$userInfo['id']?>").css("top")
    }).done(function(resp){})
  }
});

//mimic gravity
setInterval(function () {
  if (platformer.touching_lava()) {
    platformer.respawn();
    $.post("/games/test/updatePos", {
      xPos: $("#<?=$userInfo['id']?>").css("left"),
      yPos: $("#<?=$userInfo['id']?>").css("top")
    }).done(function(resp){})
  }
  if (!platformer.touching_platform()) {
    //The number represents how strong the gravity is. The 1.5 means how much pixels per milisecond the game will make the player further from the top
    platformer.gravity(.8);
  } else {
    platformer.acceleration = 0;
  }
});
</script>