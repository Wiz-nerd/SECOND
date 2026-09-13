<?php
$errors = array();
$IP = hash('ripemd160', $_SERVER['REMOTE_ADDR']);
$allowReg = 1;
$diamonds = 10;

if(isset($_POST['uname']) && isset($_POST['pwd1']) && isset($_POST['pwd2']) && isset($_POST['h-captcha-response'])){
  $uname = mysqli_real_escape_string($conn,$_POST['uname']);
  $pass = mysqli_real_escape_string($conn,$_POST['pwd1']);
  $pass2 = mysqli_real_escape_string($conn,$_POST['pwd2']);
  $usernameCheck = $conn->prepare("SELECT * FROM `brz_users` WHERE `username`=?");
  $usernameCheck->bind_param("s", $uname);
  $usernameCheck->execute();
  $UCheck = $usernameCheck->get_result();
  if(mysqli_num_rows($UCheck) > 0){
    $errors[] = 'Username has already been taken.';
  }else{
    if($allowReg == 0){
      $errors[] = 'Account creation is currently disabled.';
    }else{
      $accountLimitCheck = $conn->prepare("SELECT * FROM `brz_users` WHERE `ip`=?");
      $accountLimitCheck->bind_param("s", $IP);
      $accountLimitCheck->execute();
      $ACheck = $accountLimitCheck->get_result();
      if(mysqli_num_rows($ACheck) > 99){
        $errors[] = 'You can only have 100 accounts.';
      }else{
        if($pass != $pass2){
          $errors[] = 'Passwords do not match.';
        }else{
          $u = strlen($uname);
          if($u < 3 || $u > 23){
            $errors[] = 'Username has to be between 2-23 characters.';
          }else{
            $regex = '/\\A[a-z\\d]+(?:[.-][a-z\\d]+)*\\z/i';
            if(!preg_match_all($regex, $uname)){
              $errors[] = 'Invalid User Syntax.';
            }else{
              $p = strlen($pass);
              if($p < 6 || $p > 200){
                $errors[] = 'Password has to be between 6-200 characters.';
              }else{
                $data = array(
                  'secret' => "0xB1aA3eD27A0d61412EF8EBC54f928eDacEa7e51b",
                  'response' => $_POST['h-captcha-response']
                );
                $verify = curl_init();
                curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
                curl_setopt($verify, CURLOPT_POST, true);
                curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
                curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($verify);
                $responseData = json_decode($response);
                if(!$responseData->success) {
                  $errors[] = "Please complete the hCaptcha challenge.";
                }else{
                  $checkPastName = $conn->query("SELECT * FROM `past_names` WHERE `username`='$_POST[uname]'");
                  if($checkPastName->num_rows != 0){
                    $errors[] = "Username is already taken"; 
                  }else{
                    $newPass = password_hash($pass, PASSWORD_DEFAULT);
                    $IP = hash('ripemd160', $_SERVER['REMOTE_ADDR']);
                    $addUser = $conn->prepare("INSERT INTO `brz_users` (`id`, `username`, `passsword`, `ip`, `join_date`, `last_online`, `diamonds`) VALUES(NULL, ?, ?, ?, ?, ?, 10)");
                    $addUser->bind_param("sssii", $uname, $newPass, $IP, $time, $time);
                    $addUser->execute() or die(mysqli_error($conn));
                    $userID = $conn->insert_id;
                    $_SESSION['id'] = $userID;
                    die("<span style='margin-top:5px;margin-left:5px;background:lime;color:black;border-radius:5px;'>Account Created, Click 'home'</span>");
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}
function RandName(){
  $names = [
    'superBeast',
    'Eliminator',
    'T3rminator',
    'Fishy',
    'GreenGoat',
    'RedLizard',
    'WhiteDog',
    'MrCool',
    'Redemptor',
    'MegaKnight',
    'Astronaut',
    'N0ah',
    'Avenger',
    'JPFan',
    'UltraColin',
    'N1ck'
  ];
  $random = $names[ Rand(0, count($names)-1) ];
  $suggestion = $random . rand(1, 99999);

  echo $suggestion;
}
?>