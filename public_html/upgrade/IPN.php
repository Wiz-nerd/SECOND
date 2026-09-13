<?php
session_start();
$enableSandbox = false;
$pricemultiplier = 1;

$dbConfig = [
  'host' => 'localhost',
  'username' => '',
  'password' => '',
  'name' => ''
];

// Replace the holz.brickorzo.com links with your own website link

$paypalConfig = [
  'email' => '', //The email of your paypal account goes here
  'return_url' => "https://holz.brickorzo.com/upgrade/success.php",
  'cancel_url' => "https://holz.brickorzo.com/upgrade/canceled.php",
  'notify_url' => "https://holz.brickorzo.com/upgrade/IPN.php"
];

$paypalUrl = $enableSandbox ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr';

require 'functions.php';

if (!isset($_POST["txn_id"]) && !isset($_POST["txn_type"])) {
  if (isset($_POST['item_number'])) {
    $pid = intval($_POST['item_number']);

    if ($pid == 1) {
      $itemName = 'Brickorzo Plus, one month';
      $itemAmount = 2.99 * $pricemultiplier;
      $itemAmount = (round($itemAmount / 0.05) * 0.05 - 0.01);
    } elseif ($pid == 2) {
      $itemName = '50 Shards';
      $itemAmount = 0.50 * $pricemultiplier;
      $itemAmount = (round($itemAmount / 0.05) * 0.05);
    } elseif ($pid == 3) {
      $itemName = '250 Shards';
      $itemAmount = 2.50 * $pricemultiplier;
      $itemAmount = (round($itemAmount / 0.05) * 0.05);
    } elseif ($pid == 4) {
      $itemName = '450 Shards';
      $itemAmount = 4.50 * $pricemultiplier;
      $itemAmount = (round($itemAmount / 0.05) * 0.05);
    } else {
      $itemName = 'Brickorzo Plus, one month';
      $itemAmount = 2.99 * $pricemultiplier;
      $itemAmount = (round($itemAmount / 0.05) * 0.05 - 0.01);
    }
  } else {
    echo "Something went wrong while retrieving the product id, please try again later.";
    die();
  }

  $data = [];
  foreach ($_POST as $key => $value) {
    $data[$key] = stripslashes($value);
  }

  $data['business'] = $paypalConfig['email'];

  $data['return'] = stripslashes($paypalConfig['return_url']);
  $data['cancel_return'] = stripslashes($paypalConfig['cancel_url']);
  $data['notify_url'] = stripslashes($paypalConfig['notify_url']);
  $data['item_name'] = $itemName;
  $data['amount'] = $itemAmount;
  $data['currency_code'] = 'CAD';
  $queryString = http_build_query($data);
  header('Location:' . $paypalUrl . '?' . $queryString);
  exit();

} else {
  $db = new mysqli($dbConfig['host'], $dbConfig['username'], $dbConfig['password'], $dbConfig['name']);

  $data = [
    'item_name' => $_POST['item_name'],
    'item_number' => $_POST['item_number'],
    'payment_status' => $_POST['payment_status'],
    'payment_amount' => $_POST['mc_gross'],
    'payment_currency' => $_POST['mc_currency'],
    'txn_id' => $_POST['txn_id'],
    'receiver_email' => $_POST['receiver_email'],
    'payer_email' => $_POST['payer_email'],
    'custom' => $_POST['custom'],
  ];

  if (verifyTransaction($_POST) && checkTxnid($data['txn_id'])) {
    if (addPayment($data) !== false) {
      // Payment successfully added.
      //discord("@everyone Received a payment.\nPost data:\n```\n". print_r($_POST, true) . "\n```", "Payment received.");
    }
  }
}