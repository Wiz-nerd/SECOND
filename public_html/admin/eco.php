<?php
$conn = mysqli_connect('localhost','id21046293_bdb','Toddlogan08!','id21046293_bdb');

include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
if (isset($_POST['submit'])) {
    $diamonds = $numToDel = $_POST['diamonds'];
    $userId = $_POST['user_id'];
    $sql = "UPDATE brz_users SET diamonds = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $diamonds, $userId);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Set diamonds to $diamonds diamonds for user $userId.";
    } else {
        echo "Failed to add diamonds.";
    }

}
?>
<center>
    <div class="column is-6">
        <div class="box">
            <form method="POST" action="">
        <label style="color:white;" for="user_id">User ID:</label>
        <input type="text" placeholder="UserId" name="user_id" id="user_id" required>
        <br>
        <label style="color:white;" for="diamonds">Amt:</label>
        <input type="number" placeholder="diamonds amt" name="diamonds" id="diamonds" required>
        <br>
        <input style="width: 80%; height: 40px; font-size: 18px; cursor: pointer; margin-top: 5px;" type="submit" name="submit" value="OK">
    </form>
</div>
</div>
</center>