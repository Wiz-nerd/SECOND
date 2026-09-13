<?php
$aray = array();
foreach($aray as $e){
 echo $e; 
}
if(isset($_POST['add'])){
 array_push($aray, $_POST['add']);
 foreach($aray as $e){
 echo $e; 
}
}
?>
<form method="post">
  <input name="add">
</form>