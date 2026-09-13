<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');

$errors = array();
$getSiteSettings = $conn->prepare("SELECT * FROM `brz_site_conf`");
$getSiteSettings->execute();
$setResult = $getSiteSettings->get_result();
$settings = $setResult->fetch_assoc();

if($settings['maintenance'] != "yes")
  header("Location: /home/");

if(isset($_POST['code'])){
  $code = mysqli_real_escape_string($conn, $_POST['code']);
  if($code != $settings['maintenance_code']){
    $errors[] = 'Invalid Code';
  }else{
    $_SESSION['maint'] = rand(0, 99);
    session_start();
    header("Location: /home/");
  }
}
?>
<!DOCTYPE html>
<head>
  <title>Maintenance | <?=$site[0]?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/assets/css/tooltip.css" />
  <link rel="stylesheet" href="/assets/css/bulma.css" />
  <link rel="stylesheet" href="/assets/css/site.css" />
  <link rel="stylesheet" href="/assets/css/animate.css" />
  <link rel="stylesheet" href="/assets/css/font-awesome.css" />
</head>
<html>
  <body>
    <div class="container">
      <div class="content">
        <div style="height:100px;"></div>
        <div class="column is-6 is-centered">
          <?php foreach($errors as $error){ ?>
          <div class="notification is-danger">
            <?=$error?>
          </div>
          <?php } ?>
          <div class="box">
            <img class="image is-128x128 is-centered" src="<?=$site[1]?>">
            <h4 style="color: orange;"><?=$site[0]?></h4>
            <p class="has-text-white">We are currently under maintenance, <b>please come back later.</b></p>
            
            <div style="height:10px"></div>
         
            <div style="height:1px"></div>
          </div>
        </div>
        </body>
      </html>
    <script>
      if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
      }
    </script>