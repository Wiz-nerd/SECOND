<?php
require($_SERVER['DOCUMENT_ROOT'] . '/config/vendor/autoload.php');

$options = array(
  'cluster' => 'us2',
  'useTLS' => true
);
  
$pusher = new Pusher\Pusher(
  '3dc76a3eb80033edcef5',
  'b595ffc3115e4f02006a',
  '1412704',
  $options
);

$id = time();

$presence_data = array(
  'id' => $id
);

echo $pusher->presence_auth(
  $_POST['channel_name'],
  $_POST['socket_id'],
  $id,
  $presence_data
);

exit();

?>