<?php
  function verifyTransaction($data) {
  global $paypalUrl;

  $req = 'cmd=_notify-validate';
  foreach ($data as $key => $value) {
    $value = urlencode(stripslashes($value));
    $value = preg_replace('/(.*[^%^0^D])(%0A)(.*)/i', '${1}%0D%0A${3}', $value); // IPN fix
    $req .= "&$key=$value";
  }

  $ch = curl_init($paypalUrl);
  curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
  curl_setopt($ch, CURLOPT_SSLVERSION, 6);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
  curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close'));
  $res = curl_exec($ch);

  if (!$res) {
    $errno = curl_errno($ch);
    $errstr = curl_error($ch);
    curl_close($ch);
    throw new Exception("cURL error: [$errno] $errstr");
  }

  $info = curl_getinfo($ch);

  // Check the http response
  $httpCode = $info['http_code'];
  if ($httpCode != 200) {
    throw new Exception("PayPal responded with http code $httpCode");
  }

  curl_close($ch);

  return $res === 'VERIFIED';
}

/**
 * Check we've not already processed a transaction
 *
 * @param string $txnid Transaction ID
 * @return bool True if the transaction ID has not been seen before, false if already processed
 */
function checkTxnid($txnid) {
  global $db;

  $txnid = $db->real_escape_string($txnid);
  $results = $db->query('SELECT * FROM `payments` WHERE txnid = \'' . $txnid . '\'');
  if(!$results){
   throw new Exception(mysqli_error($db)); 
  }

  return ! $results->num_rows;
}

function discord($message, $username)
{
  $data = array(
    "content" => $message,
    "username" => $username
  );
  $curl = curl_init("discord webhook url if you want to get notified when you get a payment or whatever");
  curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
  return curl_exec($curl);
}

/**
 * Add payment to database
 *
 * @param array $data Payment data
 * @return int|bool ID of new payment or false if failed
 */
function addPayment($data) {
  global $db;

  if (is_array($data)) {
    $custom = explode(":", $data['custom']);
    $user_name = $custom[0];
    $user_id = intval($custom[1]);

    $stmt = $db->prepare('INSERT INTO `payments` (txnid, payment_amount, payment_status, itemid, createdtime, userid) VALUES(?, ?, ?, ?, ?, ?)');
    $stmt->bind_param(
      'sdsssi',
      $data['txn_id'],
      $data['payment_amount'],
      $data['payment_status'],
      $data['item_number'],
      date('Y-m-d H:i:s'),
      $user_id
    );
    $stmt->execute();
    if(!$stmt){
     throw new Exception(mysqli_error($db)); 
    }
    $stmt->close();

    $is_currency_purchase = false;

    if ($data['item_number'] == 1) {
      $level = "1";
      $expire = time() + (86400 * 31);
      $awarditem = 282;
    } else if ($data['item_number'] == 2) {
      $brick_amt = 50;
      $is_currency_purchase = true;
      $item = 257;
      $o = 0;
      $stmt = $db->prepare('INSERT INTO `brz_inv` VALUES(NULL, ?, ?, ?)');
      $stmt->bind_param('iii',$item,$user_id,$o);
      $stmt->execute();
    } else if ($data['item_number'] == 3) {
      $brick_amt = 250;
      $is_currency_purchase = true;
      $item = 259;
      $o = 0;
      $stmt = $db->prepare('INSERT INTO `brz_inv` VALUES(NULL, ?, ?, ?)');
      $stmt->bind_param('iii',$item,$user_id,$o);
      $stmt->execute();
    } else if ($data['item_number'] == 4) {
      $brick_amt = 450;
      $is_currency_purchase = true;
      $item = 258;
      $o = 0;
      $stmt = $db->prepare('INSERT INTO `brz_inv` VALUES(NULL, ?, ?, ?)');
      $stmt->bind_param('iii',$item,$user_id,$o);
      $stmt->execute();
    } else {
      $level = "1";
      $expire = time() + (86400 * 31);
      $awarditem = 280;
    }

    $databaseconf = [
    'host' => 'localhost',
    'username' => '',
    'password' => '',
    'name' => ''
    ];

    $con = mysqli_connect($databaseconf['host'], $databaseconf['username'], $databaseconf['password']);
    mysqli_select_db($con, $databaseconf['name']);

    if (!$is_currency_purchase) {
      $upduser = mysqli_query($con, "UPDATE brz_users SET `membership`='$level', `membership_expire`='$expire' WHERE id='$user_id'");
    } else {
      $upduser = mysqli_query($con, "UPDATE brz_users SET `shards` = `shards` + '$brick_amt' WHERE id='$user_id'");
    }



    return $db->insert_id;
  }

  return false;
}
?>