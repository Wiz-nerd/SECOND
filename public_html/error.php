<?php
switch($_GET['code']){
case '404':
$values = ['Not Found','404','Path could not be found'];
break;
case '403':
$values = ['Not Authorized','403','You are not allowed to access this resource'];
break;
case '500':
$values = ['Internal Server Error','500','Something went wrong on our end, try again later'];
break;
default:
header("Location: /home/");
}
$pageName = $values[0];
include('./config/param.php');
include('./config/header.php');
?>
<center>
<div class="column is-6">
<div class="box">
<img src="/assets/images/error.png" alt="Error loading image" width="170" height="170">
<h1 class="title has-text-white"><?=$values[1] ."&nbsp;". $values[0]?></h1>
<p class="subtitle has-text-white">
<?=$values[2]?>
</p>
</div>
</div>
<?php
footer();
?>