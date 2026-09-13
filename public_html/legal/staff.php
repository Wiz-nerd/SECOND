<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$pageName = "Staff Team";
$getStaff = $conn->prepare("SELECT * FROM `brz_users` WHERE `rank`!=? ORDER BY `id` ASC");
$getStaff->bind_param("i", $o);
$getStaff->execute();
$staffResults = $getStaff->get_result();
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
?>
<section class="section">
<section class="container">
	<div class="content">
		<h1 class="title has-text-white">Staff Team</h1>
		<?php foreach($staffResults as $staff){ 
			$staffU = userInfo($staff['id']);
			?>
			<div class="box">
				<div class="columns">
					<div class="column is-1">
						<a href="/profile/<?=$staffU['username']?>"><img width="50" src="<?=$staffU['avatar_img']?>"></a>
					</div>
					<div class="column">
						<a href="/profile/<?=$staffU['username']?>"><h3 class="title has-text-white"><?=htmlentities($staffU['username'])?></h3></a>
						<p class="has-text-white"><?=htmlentities($staffU['bio'])?></p>
					</div>
				</div>
              </div>
				<?php } ?>
			</div>
		</div>
	</section>
</section>

<?=footer()?>