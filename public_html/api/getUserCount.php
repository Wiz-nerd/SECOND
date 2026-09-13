<?php
include("../config/param.php");
header("Content-type: json");
$count = $conn->query("SELECT * FROM `brz_users`")->num_rows;
$json = ["users" => "$count"];
$encode = json_encode($json);
echo $encode;
?>