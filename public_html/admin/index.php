<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$pageName = "Admin Panel";
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');

if($user['rank'] == 0){
	unset($_SESSION['admin']);
	header("Location:/admin/login/");
}

if(!isset($_SESSION['id'])){
	header("Location: /login/");
}

switch($user['rank']){
	case 1:
	$tabs = [
	'Statistics' => 'getTab(1)',
	'Users' => 'getTab(2)',
	'Items' => 'getTab(3)',
    'Approval' => 'getTab(6)',
	'Create Item' => 'getTab(4)'
	];
	break;
	case 2:
	$tabs = [
	'Statistics' => 'getTab(1)',
	'Users' => 'getTab(2)',
	'Items' => 'getTab(3)',
	'Create Item' => 'getTab(4)',
    'Approval' => 'getTab(6)'
	];
	break;
	case 3:
	$tabs = [
	'Statistics' => 'getTab(1)',
	'Users' => 'getTab(2)',
	'Items' => 'getTab(3)',
    'Approval' => 'getTab(6)'
	];
	break;
	case 4:
	$tabs = [
	'Statistics' => 'getTab(1)',
	'Users' => 'getTab(2)',
	'Items' => 'getTab(3)',
	'Create Item' => 'getTab(4)',
    'Approval' => 'getTab(6)'
	];
	break;
	case 5:
	$tabs = [
	'Statistics' => 'getTab(1)',
	'Users' => 'getTab(2)',
	'Items' => 'getTab(3)',
	'Create Item' => 'getTab(4)',
	'Approval' => 'getTab(6)',
	'Staff Members' => 'getTab(8)',
	'Website Settings' => 'getTab(9)',
	'Manage Subscriptions' => 'getTab(10)'
	];
	break;
	default:
	$tabs = [
	'Go away' => 'getTab(1)'
	];
}
?>
<div class="container">
	<div class="content">
		<div class="box">
			<div class="columns">
				<div class="column is-3">
					<center>
						<img src="<?=$user['avatar_img']?>"></img>
					</center>
				</div>
				<div class="column is-3">
					<h3 class="has-text-white">Information</h3>
					<p class="has-text-white">Username: <?=$user['username']?></p>
					<p class="has-text-white">Rank: <?=$rank?></p>
					<p class="has-text-white">Admin Points: <?=$user['admin_points']?></p>
				</div>
				<div class="column is-3">
					<h3 class="has-text-white">Recent Actions</h3>
					<p class="has-text-white">Soon</p>
				</div>
				<div class="column is-3">
					<h3 class="has-text-white">Admin Points To Shards</h3>
					<p class="has-text-white">10 Admin Points <i class="fas fa-arrow-right"></i> 1 Shard</p>
					<input class="input is-dark" placeholder="Amount of points to convert">
					<div style="height:10px"></div>
					<button class="button is-danger">Convert</button>
				</div>
			</div>
			<div class="buttons is-centered">
				<?php foreach($tabs as $tab => $link){ ?>
				<button class="button is-danger" onclick="<?=$link?>"><a><span><?=$tab?></span></a></button>
				<?php } ?>
			</div>
		</div>
		<div class="box">
			<div id="page"></div>
		</div>
	</div>
</div>
</div>
<?=footer()?>
<script>
$(document).ready(function(){
	$("#page").load("/admin/pages/stats");
})

function getTab(number) {

	switch(number){
		case 1:
		$("#page").load("/admin/pages/stats");
		break;
		case 2:
		$("#page").load("/admin/pages/users");
		break;
		case 3:
		$("#page").load("/admin/pages/items");
		break;
		case 4:
		$("#page").load("/admin/pages/create");
		break;
		case 5:
		$("#page").load("/admin/pages/reports");
		break;
		case 6:
		$("#page").load("/admin/pages/forum");
		break;
		case 7:
		$("#page").load("/admin/pages/eco");
		break;
		case 8:
		$("#page").load("/admin/pages/staff");
		break;
		case 9:
		$("#page").load("/admin/pages/site");
		break;
		case 10:
		$("#page").load("/admin/test");
		break;
		case 11:
		$("#page").load("/admin/test");
		break;
		default:
	}

}
</script>