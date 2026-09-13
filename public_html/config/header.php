<?php
//header("Content-Security-Policy: default-src 'self'");
header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
header("Pragma: no-cache"); //HTTP 1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

//or, if you DO want a file to cache, use:
header("Cache-Control: max-age=2592000");
//include('helper.php');
date_default_timezone_set('America/Chicago');
$site = [
'Brickorzo Reborn',
'/assets/images/logo.png',
'http://brikorzo.fun',
'http://brikorzo.fun/blog',
'http://brikorzo.fun/admin',
'http://brikorzo.fun',
'/assets/images/avatar.png',
'Bucks',
'https://twitter.com/brickreborn',
'https://discord.gg/brickorzo20'
];

$navbar = [
'Home',
'Games',
'Shop',
'Forums'
];

$dropdown = [
'Profile',
'Avatar',
'Settings',
'Logout'
];

$dropdown2 = [
'Users',
'Promocodes'
];

$stmt = $conn->prepare("SELECT * FROM `brz_site_conf`");
$stmt->execute();
$website = $stmt->get_result();

if($website->num_rows < 1){
  die("The website is not correctly configured. Please go to the brz_site_conf table and insert a row.");
}

$stats = $website->fetch_assoc();

if(!isset($_SESSION['maint'])){
  if($stats['maintenance'] == "yes")
    header("Location: /maintenance");
}

$ipbans = $conn->prepare("SELECT * FROM `ip_bans` WHERE `ip`=? AND `active`=?");
$ipbans->bind_param("si", $user['ip'], $i);
$ipbans->execute();
$website2 = $ipbans->get_result();

if($website2->num_rows > 0){
  die("You have been IP banned. You will also be banned on sight if you ever enter our platform again. You are no longer welcome here.");
}

if(isset($_SESSION['id'])){
if($user['membership'] != 0){
  $nextShards = $user['shards'] + 150;
}else{
  $nextShards = $user['shards'] + 100;
}

if($time <= $user['daily_shards']){
}else{
  {
    $stmt = $conn->prepare("UPDATE `brz_users` SET `shards`=? WHERE `id`=?");
    $stmt->bind_param("ii", $nextShards, $user['id']);
    $stmt->execute();
  }

  $nexttime = $time + 86400;
  $stmt = $conn->prepare("UPDATE `brz_users` SET `daily_shards`=? WHERE `id`=?");
  $stmt->bind_param("ii", $nexttime, $user['id']);
  $stmt->execute();
}
}
?>
<head>
  <title><?=(isset($pageName)) ? $pageName . " | $site[0]" : "$site[0]"?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/assets/css/tooltip.css" />
  <link rel="stylesheet" href="/assets/css/bulma.css" />
  <link rel="stylesheet" href="/assets/css/site.css" />
  <link rel="stylesheet" href="/assets/css/animate.css" />
  <link rel="stylesheet" href="/assets/css/font-awesome.css" />
  <script src="/assets/js/jquery.min.js"></script>
  <script src='https://js.hcaptcha.com/1/api.js' async defer></script>
  <link rel="shortcut icon" href="<?=$site[1]?>">
</head>
<nav class="navbar is-dark">
  <div class="navbar-brand">
    <a class="navbar-item" href="<?=$site[5]?>">
      <img src="<?=$site[1]?>" width="28" height="28">
    </a>
    <a role="button" onclick="toggleBurger()" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navMenuColordark-example">
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
      <span aria-hidden="true"></span>
    </a>
  </div>
  <div style="background-color:#363636;color:white;" id="navMenuColordark-example" class="navbar-menu">
    <div class="navbar-start">
      <?php
      foreach($navbar as $nav){
        echo"<a class='navbar-item' href='/".strtolower($nav)."/'>$nav</a>";
      }
      ?>
      <div class="navbar-item has-dropdown is-hoverable">
        <a class="navbar-link">
          More
        </a>

        <div class="navbar-dropdown">
          <?php foreach($dropdown2 as $drop2){
            echo"<a style='color:#fff;' class='navbar-item' href='/".strtolower($drop2)."/'>
            $drop2
            </a>";
          } ?>
        </div>
      </div>
    </div>
    <?php
    if(!isset($_SESSION['id'])){?>
    <div class="navbar-end">
      <div class="navbar-item">
        <div class="buttons">
          <a style="background-color:#fe8447;" href="/register/" class="button is-primary">
            <strong>Sign up</strong>
          </a>
          <a href="/login/" class="button is-black">
            Log in
          </a>
        </div>
      </div>
    </div>
    <?php
  }else{ ?>
  <div class="navbar-end">
    <a class="navbar-item">
      

<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 20010904//EN"
 "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">
<svg style="margin-right: 4px;" version="1.0" xmlns="http://www.w3.org/2000/svg"
 width="20px" height="20px" viewBox="0 0 512.000000 512.000000"
 preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
fill="white" stroke="none">
<path d="M2200 5111 c-72 -22 -151 -82 -215 -165 -58 -75 -137 -232 -172 -344
-25 -77 -28 -101 -27 -207 1 -86 8 -142 23 -198 28 -106 102 -278 176 -406 58
-103 62 -107 110 -123 85 -29 182 -23 331 19 112 33 142 38 229 37 90 -1 115
-5 249 -47 82 -26 156 -47 163 -47 21 0 144 73 243 144 95 67 269 236 318 307
48 71 98 176 132 277 71 211 17 359 -143 392 -29 5 -173 12 -322 13 -368 5
-400 14 -645 179 -161 108 -202 132 -278 157 -62 21 -126 26 -172 12z"/>
<path d="M1238 3904 c-33 -17 -58 -62 -58 -103 0 -50 39 -85 122 -109 69 -20
209 -95 272 -146 l28 -24 -63 -11 c-103 -18 -392 -110 -416 -133 -30 -28 -38
-76 -20 -121 15 -34 59 -67 91 -67 8 0 85 22 170 49 184 59 252 74 324 69 l54
-3 -129 -110 c-437 -375 -766 -805 -891 -1165 -56 -161 -75 -278 -76 -455 0
-141 3 -173 27 -268 46 -186 106 -315 220 -477 251 -355 536 -574 906 -696
451 -149 1051 -174 1518 -64 247 58 516 174 675 293 498 369 621 977 336 1667
-188 457 -500 842 -1134 1402 -14 13 -62 24 -160 39 -76 12 -173 32 -214 46
-43 14 -111 27 -160 30 -75 5 -101 1 -215 -28 -159 -41 -298 -46 -378 -15 -82
31 -205 104 -355 208 -152 107 -250 160 -347 190 -72 21 -91 22 -127 2z m1477
-877 c64 -35 85 -89 85 -212 l0 -101 73 -23 c243 -78 408 -238 395 -383 -4
-41 -11 -57 -35 -79 -68 -60 -144 -44 -214 47 -51 67 -122 131 -177 162 l-42
23 0 -303 0 -304 97 -27 c237 -67 372 -187 428 -382 21 -72 24 -217 6 -295
-49 -209 -208 -357 -442 -412 l-89 -21 0 -94 c0 -110 -13 -147 -65 -190 -63
-52 -150 -50 -207 4 -46 43 -58 81 -58 187 0 104 4 97 -70 111 -66 12 -161 47
-228 83 -191 105 -302 326 -219 436 45 59 120 60 174 3 17 -18 50 -59 74 -92
66 -92 129 -149 199 -183 l65 -31 3 336 c1 185 0 338 -2 340 -2 3 -46 17 -98
32 -118 34 -215 88 -287 160 -241 241 -185 650 111 814 70 39 163 72 233 82
l45 7 0 97 c0 80 4 104 23 141 27 54 84 90 142 90 23 0 57 -10 80 -23z"/>
<path d="M2403 2464 c-23 -9 -63 -34 -89 -57 -121 -107 -128 -297 -14 -387 32
-25 145 -80 165 -80 3 0 5 122 5 270 0 210 -3 270 -12 269 -7 0 -32 -7 -55
-15z"/>
<path d="M2800 1250 c0 -194 3 -290 10 -290 6 0 27 9 48 19 109 56 182 167
182 276 0 34 -5 77 -11 96 -25 73 -91 137 -178 170 l-51 19 0 -290z"/>
</g>
</svg>
<?=$user['shards']?> 

    </a>
    <a class="navbar-item">
<svg fill="white" style="margin-right: 4px;" height="20px" width="20px" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
	 viewBox="0 0 512 512" xml:space="preserve">
<g>
	<g>
		<path d="M402.06,22.234H109.941L0,159.629l256.058,330.137L512,159.628L402.06,22.234z M483.794,151.301h-136.65l52.523-105.137
			L483.794,151.301z M384.416,39.054l-51.55,103.192L270.914,39.054H384.416z M318.684,151.301H205.08l51.646-103.202
			L318.684,151.301z M242.442,39.054l-51.645,103.202L128.84,39.054H242.442z M112.996,45.338l63.614,105.963H28.207L112.996,45.338
			z M27.873,168.121h156.728l56.001,274.274L27.873,168.121z M201.768,168.121h121.055l-66.021,269.531L201.768,168.121z
			 M340.139,168.121h143.993L273.629,439.648L340.139,168.121z"/>
	</g>
</g>
</svg>
<?=$user['diamonds']?> 

    </a>
    <a href="/friends/" class="navbar-item">
      <i class="fa fa-users" aria-hidden="true"></i>
      <?=notifs("friends","status")?>
    </a>
    <a href="/messages/" class="navbar-item">
      <i class="fas fa-envelope" aria-hidden="true"></i>
      <?=notifs("msgs_brz","read")?>
    </a>
    <a href="/trades/" class="navbar-item">
      <i class="fas fa-exchange-alt"></i>
      <?=notifsT("brz_trades_brz_orzo_brz","status")?>
    </a>
    <?php
    if($user['rank'] != 0){
      echo'<a href="/admin" class="navbar-item">
      <i class="fa fa-gavel"></i>
      </a>';
    }
    ?>
    <div class="navbar-item has-dropdown is-hoverable is-dark">
      <a class="navbar-link">
        <?=$user['username']?>
      </a>
      <div class="navbar-dropdown is-dark">
        <?php 
        foreach($dropdown as $drop){
          echo'<a href="/'.strtolower($drop).'/" class="navbar-item">
          '.$drop.'
          </a>';
        }
        ?>
      </div>
    </div>
  </div>
  <?php
}
?>
</div>
</nav>
<?php if(!empty($stats['banner'])){ ?>
<div class="box <?=$stats['banner_color']?> has-text-centered has-text-white announcement"><?=$stats['banner']?></div>
<?php 
} 
$getBans = $conn->prepare("SELECT * FROM `bans` WHERE `user`=? AND `expired`=?");
$getBans->bind_param("ii", $user['id'], $o);
$getBans->execute();
$banResult = $getBans->get_result();
if($banResult->num_rows == 1){
  echo"<script>document.title='Banned | $site[0]'</script>";
  $ban = $banResult->fetch_assoc();
  $mod = userInfo($ban['moderator']);

  if($ban['termination'] == "0"){
    if(time() - $ban['ban_length'] >= 0){
      $updateBan = $conn->prepare("UPDATE `bans` SET `expired`=? WHERE `user`=?");
      $updateBan->bind_param("ii", $i, $user['id']);
      $updateBan->execute();
      ?>
      <script>document.location = "";</script>
      <?php
      die();
    }
    ?>
    <div class="column is-6 is-centered">
      <div class="box">
        <p class="title has-text-white">You have been banned.</p>
        <p class="subtitle has-text-white"> You will be unbanned in <?=time_elapsed($ban['ban_length'])?>.</p>
        <?php
      }else{ ?>
      <div class="column is-6 is-centered">
        <div class="box">
          <p class="title has-text-white">You have been terminated.</p>
          <?php } ?>
          <hr>
          <div class="columns is-centered">
            <div class="column is-6">
              <h2 class="has-text-white is-pulled-left">Reason:</h2><br>
              <h2 class="has-text-white is-pulled-left"><?=nl2br(htmlentities($ban['ban_note']))?></h2>
            </div>
            <div class="column is-6">
              <h2 class="has-text-white is-pulled-left">Ban info:</h2><br>
              <h2 class="has-text-white is-pulled-left">Moderated by: <?=htmlentities($mod['username'])?></h2><br>
              <h2 class="has-text-white is-pulled-left">You were banned <?=time_elapsed2($ban['date_of_ban'])?></h2>
            </div>
          </div>
          <a class="button is-success is-centered" href="/logout">Logout</a>
        </div>
      </div>
    </div>
  </div>
  <?php
  footer();
  die();
}
?>