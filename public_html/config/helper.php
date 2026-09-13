<?php
$conn = mysqli_connect('localhost','id21046293_bdb','Toddlogan08!','id21046293_bdb');

function time_elapsed($ptime)
{
  $etime = $ptime - time();

  if ($etime < 1)
  {
    return 'Just now';
  }

  $a = array( 365 * 24 * 60 * 60  =>  'year',
             30 * 24 * 60 * 60  =>  'month',
             24 * 60 * 60  =>  'day',
             60 * 60  =>  'hour',
             60  =>  'minute',
             1  =>  'second'
            );

  $a_plural = array( 'year'   => 'years',
                    'month'  => 'months',
                    'day'    => 'days',
                    'hour'   => 'hours',
                    'minute' => 'minutes',
                    'second' => 'seconds'
                   );

  foreach ($a as $secs => $str)
  {
    $d = $etime / $secs;
    if ($d >= 1)
    {
      $r = round($d);
      return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . '';
    }
  }
}
function time_elapsed2($ptime)
{
  $etime = time() - $ptime;

  if ($etime < 1)
  {
    return 'Just now';
  }

  $a = array( 365 * 24 * 60 * 60  =>  'year',
             30 * 24 * 60 * 60  =>  'month',
             24 * 60 * 60  =>  'day',
             60 * 60  =>  'hour',
             60  =>  'minute',
             1  =>  'second'
            );

  $a_plural = array( 'year'   => 'years',
                    'month'  => 'months',
                    'day'    => 'days',
                    'hour'   => 'hours',
                    'minute' => 'minutes',
                    'second' => 'seconds'
                   );

  foreach ($a as $secs => $str)
  {
    $d = $etime / $secs;
    if ($d >= 1)
    {
      $r = round($d);
      return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
    }
  }
}

function getOS() { 

  $user_agent = $_SERVER['HTTP_USER_AGENT'];

  $os_platform =   "Bilinmeyen İşletim Sistemi";
  $os_array =   array(
    '/windows nt 10/i'      =>  'Windows 10',
    '/windows nt 6.3/i'     =>  'Windows 8.1',
    '/windows nt 6.2/i'     =>  'Windows 8',
    '/windows nt 6.1/i'     =>  'Windows 7',
    '/windows nt 6.0/i'     =>  'Windows Vista',
    '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
    '/windows nt 5.1/i'     =>  'Windows XP',
    '/windows xp/i'         =>  'Windows XP',
    '/windows nt 5.0/i'     =>  'Windows 2000',
    '/windows me/i'         =>  'Windows ME',
    '/win98/i'              =>  'Windows 98',
    '/win95/i'              =>  'Windows 95',
    '/win16/i'              =>  'Windows 3.11',
    '/macintosh|mac os x/i' =>  'Mac OS X',
    '/mac_powerpc/i'        =>  'Mac OS 9',
    '/linux/i'              =>  'Linux',
    '/ubuntu/i'             =>  'Ubuntu',
    '/iphone/i'             =>  'iPhone',
    '/ipod/i'               =>  'iPod',
    '/ipad/i'               =>  'iPad',
    '/android/i'            =>  'Android',
    '/blackberry/i'         =>  'BlackBerry',
    '/webos/i'              =>  'Mobile'
  );

  foreach ( $os_array as $regex => $value ) { 
    if ( preg_match($regex, $user_agent ) ) {
      $os_platform = $value;
    }
  }   
  return $os_platform;
}

function getBrowser() {
  $user_agent = $_SERVER['HTTP_USER_AGENT'];

  $browser        = "Bilinmeyen Tarayıcı";
  $browser_array  = array(
    '/msie/i'       =>  'Internet Explorer',
    '/firefox/i'    =>  'Firefox',
    '/safari/i'     =>  'Safari',
    '/chrome/i'     =>  'Chrome',
    '/edge/i'       =>  'Edge',
    '/opera/i'      =>  'Opera',
    '/netscape/i'   =>  'Netscape',
    '/maxthon/i'    =>  'Maxthon',
    '/konqueror/i'  =>  'Konqueror',
    '/mobile/i'     =>  'Handheld Browser'
  );

  foreach ( $browser_array as $regex => $value ) { 
    if ( preg_match( $regex, $user_agent ) ) {
      $browser = $value;
    }
  }
  return $browser;
}

function bbcode($text){

  $text = htmlentities($text);

  $find = [
    '~\[b\](.*?)\[/b\]~s',
    '~\[i\](.*?)\[/i\]~s',
    '~\[u\](.*?)\[/u\]~s',
    '~\[s\](.*?)\[/s\]~s'
  ];
  $replace = [
    '<b>$1</b>',
    '<i>$1</i>',
    '<u>$1</u>',
    '<s>$1</s>'
  ];

  $text = preg_replace($find,$replace,$text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(www.brickorzo.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(www.gyazo.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(www.twitter.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(www.discord.gg)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(www.google.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(www.reddit.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(www.imgur.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(www.youtube.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(brickorzo.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(gyazo.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(twitter.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(discord.gg)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(google.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(reddit.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(imgur.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);
  $text = preg_replace('|([\w\d]*)\s?(https?://(youtube.com)[^\s\]\[\<\>]*/?)|i', '$1 <a href="$2">$2</a>', $text);

  return $text;
}

function filter($swear){
  $bannedWords = [
    'fuck',
    'shit',
    'fucking',
    'nigger',
    'nigga',
    'bitch',
    'dipshit',
    'retard',
    'kys',
    'kill yourself',
    'faggot',
    'tranny',
    'negro',
    'cock',
    'cum',
    'pussy',
    'sex',
    'porn',
    'pornhub.com',
    'hentaihaven.org',
    'discord.gg',
    'discord.com',
    'retarded',
    'brick luke deez nuts',
    'bldn',
    'skullfuck',
    'boobs',
    'hentai',
    'dick',
    'penis',
    'anal',
    'vagina'
  ];

  $filtered = str_ireplace($bannedWords, '*****', $swear);
  return $filtered;
}

function notifs($table, $row){
  global $conn;
  $o = 0;

  $stmt = $conn->prepare("SELECT * FROM `$table` WHERE `$row`=? AND `reciever`=?");
  $stmt->bind_param("ii", $o, $_SESSION['id']);
  $stmt->execute();
  $results = $stmt->get_result();

  if(mysqli_num_rows($results) != 0){
    echo'&nbsp;&nbsp;<span class="tag is-danger is-rounded">'.mysqli_num_rows($results).'</span>';
  }
}

function notifsT($table, $row){
  global $conn;
  $o = "p";

  $stmt = $conn->prepare("SELECT * FROM `$table` WHERE `$row`=? AND `reciever`=?");
  $stmt->bind_param("si", $o, $_SESSION['id']);
  $stmt->execute();
  $results = $stmt->get_result();

  if(mysqli_num_rows($results) != 0){
    echo'&nbsp;&nbsp;<span class="tag is-danger is-rounded">'.mysqli_num_rows($results).'</span>';
  }
}

function check_hex_color($colorCode) {
  $colorCode = ltrim($colorCode, '#');

  if (ctype_xdigit($colorCode) && (strlen($colorCode) == 6 || strlen($colorCode) == 3))
    return true;

  else return false;
}

function checkRemoteFile($url){
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  // don't download content
  curl_setopt($ch, CURLOPT_NOBODY, 1);
  curl_setopt($ch, CURLOPT_FAILONERROR, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

  $result = curl_exec($ch);
  curl_close($ch);

  if($result !== FALSE){
    return true;
  }else{
    return false;
  }
}

function determineItem(array $weightedValues) {
  $rand = mt_rand(1, (int) array_sum($weightedValues));
  foreach ($weightedValues as $key => $value) {
    $rand -= $value;
    if ($rand <= 0) {
      return $key;
    }
  }
}

function calculateRAP(int $id, bool $calculate){
  global $conn;
  if($calculate){
    $getSales = $conn->prepare("SELECT * FROM `item_sales` WHERE `item_id`=?");
    $getSales->bind_param("i", $id);
    $getSales->execute();
    $salesR = $getSales->get_result();

    $totalprice = 0;

    foreach($salesR as $id){
      $totalprice += $id["price"];
    }

	$rap = $salesR->num_rows > 0 ? round($totalprice / $salesR->num_rows) : 0;
    
    $update = $conn->prepare("UPDATE `items_brz` SET `rap`=? WHERE `id`=?");
    $update->bind_param("ii", $rap, $id["item_id"]);
    $update->execute();
  }else{
    $getItem = $conn->prepare("SELECT * FROM `items_brz` WHERE `id`=?");
    $getItem->bind_param("i", $id);
    $getItem->execute();
    $itemRes = $getItem->get_result();
    $rap = $itemRes->fetch_assoc()["rap"];
   }

  return $rap;
}