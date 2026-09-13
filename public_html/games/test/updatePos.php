<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(isset($_POST['xPos']) && isset($_POST['yPos'])){
 	$updatePosition = $conn->prepare("UPDATE `brz_player_positions` SET `x_pos`=?, `y_pos`=? WHERE `user_id`=?");
 	$updatePosition->bind_param("ddi", $_POST['xPos'], $_POST['yPos'], $user['id']);
 	$updatePosition->execute();
}
?>
