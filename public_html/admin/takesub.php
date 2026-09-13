<?php
$conn = mysqli_connect('localhost','id21046293_bdb','Toddlogan08!','id21046293_bdb');
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$pageName = "Manage Subscriptions";
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');

if (isset($_POST['submit'])) {
    $profile = $_POST['user_id'];
    $conn->query("UPDATE `brz_users` SET `sub`= 0 WHERE `id`='$profile'");

}
?>
<center>
    <div class="column is-6">
        <div class="box">
            <h1 style='color:white;'>Take Subscription</h1>
            <form method="POST" action="">
        <label style="color:white;" for="user_id">User ID:</label>
        <input type="text" placeholder="UserId" name="user_id" id="user_id" required>
        <br>
        <input style="width: 80%; height: 40px; font-size: 18px; cursor: pointer; margin-top: 5px;" type="submit" name="submit" value="OK">
    </form>
    <a href="/admin/givesub">Give subscriptions</a>
</div>
</div>
</center>