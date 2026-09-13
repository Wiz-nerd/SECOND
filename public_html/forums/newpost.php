<?php
$pageName = "New Post";
include('../config/param.php');
if(!isset($_SESSION['id'])){
    header("Location: /login/");
}
include('../config/header.php');
$getSubForums = $conn->prepare("SELECT * FROM `forum_tables`");
$getSubForums->execute();
$sub = $getSubForums->get_result();
if(isset($_GET['id'])){
$getSubForums = $conn->prepare("SELECT * FROM `forum_tables` WHERE `id`=?");
$getSubForums->bind_param("i", $_GET['id']);
$getSubForums->execute();
$sub = $getSubForums->get_result();
	if(mysqli_num_rows($sub) > 0){
}else{
	header("Location: /error/code/404");
}
}
$subforum = $sub->fetch_assoc();
?>
<center>
<div class="column is-6">
<div class="box is-centered">
<h2 class="title has-text-white">Post in <?=$subforum['sub_name']?></h2>
<p style='color:red;' id='err'></p>
<form method='post' id='create'>
<label class="label has-text-white" style='display:flex;'>Title</label>
<input type='text' id='title' class='input is-dark'><div style="height:5px"></div>
<label class="label has-text-white" style='display:flex;'>Body</label>
<textarea type='text' id='body' class='textarea is-dark'></textarea>
<br>
</div>
<button type='submit' class="button is-centered is-black">Post!</button>
</form>
</div>
</div>
<?php
footer();
?>
<script>
$("#create").submit(function(e){
    e.preventDefault();
    $.post("/forums/create", {
        title: $("#title").val(),
        body: $("#body").val(),
        topic: <?=$subforum['id']?>
    }).done(function(resp){
        if(resp != "success") {
            $("#err").text(resp);
        } else {
            window.location = "/forums/?id=<?=$subforum['id']?>";
        }
    })
})
</script>