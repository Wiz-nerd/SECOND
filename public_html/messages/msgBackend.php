<?php
$getSentMsgs = $conn->prepare("SELECT * FROM `msgs_brz` WHERE `sender`=? ORDER BY `id` DESC LIMIT 5");
$getSentMsgs->bind_param("i", $user['id']);
$getSentMsgs->execute();
$sentResults = $getSentMsgs->get_result();