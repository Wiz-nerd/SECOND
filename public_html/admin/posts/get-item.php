<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
if(!isset($_GET['query']) || $_GET['query'] == null){
	die("Invalid Request");
}

$getItem = $conn->prepare("SELECT * FROM `items_brz` WHERE `item_name`=?");
$getItem->bind_param("s", $_GET['query']);
$getItem->execute();
$itemResult = $getItem->get_result();

if($itemResult->num_rows == 0){
	die("Item does not exist");
}

$item = $itemResult->fetch_assoc();


function getItemSales($id){
	global $conn;
	$getSales = $conn->prepare("SELECT * FROM `brz_inv` WHERE `item`=?");
	$getSales->bind_param("i", $id);
	$getSales->execute();
	$result = $getSales->get_result();
	return $result;
}

switch($user['rank']){
	case 1:
	$controls = [
	'Edit Item' => 'edit',
	'Decline Item' => 'decline'
	];
	break;
	case 2:
	$controls = [
	'Edit Item' => 'edit',
	'Decline Item' => 'decline'
	];
	break;
	case 3:
	$controls = [
	'Edit Item' => 'edit',
	'Decline Item' => 'decline'
	];
	break;
	case 4:
	$controls = [
	'Edit Item' => 'edit',
	'Decline Item' => 'decline',
	'Rerender Item' => 'render'
	];
	break;
	case 5:
	$controls = [
	'Edit Item' => 'edit',
	'Decline Item' => 'decline',
	'Rerender Item' => 'render'
	];
	break;
}

switch($item['status']){
	case 'onsale':
	$price = "$item[item_price] Bucks";
	break;
	case 'offsale':
	$price = "Offsale";
	break;
	case 'free':
	$price = "Free";
	break;
	case 'limited':
	$price = "$item[item_price] Bucks";
	break;
	default:
	$price = "???";
}

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma-extensions@6.2.7/dist/css/bulma-extensions.min.css" integrity="sha256-RuPsE2zPsNWVhhvpOcFlMaZ1JrOYp2uxbFmOLBYtidc=" crossorigin="anonymous">
<input type="hidden" id="uid" value="<?=$item['id']?>">
<div class="columns">
	<div class="column is-3">
		<div class="box">
			<center>
				<img width="250" height="250" src="<?=$item['headshot']?>">
			</center>
		</div>
		<div class="box">
			<?php foreach($controls as $control => $activate){ ?>
			<button value="<?=$activate?>" id="<?=$activate?>" class="button is-danger is-fullwidth"><?=$control?></button>
			<div style="height:10px;"></div>
			<?php } ?>
		</div>
	</div>
	<div class="column is-7">
		<div class="box">
			<h3 class="subtitle has-text-white">Information</h3>
			<b class="has-text-white">ID: </b><text class="has-text-white"><?=$item['id']?></text><br>
			<b class="has-text-white">Item Name: </b><text class="has-text-white"><?=$item['item_name']?></text><br>
			<b class="has-text-white">Price: </b><text class="has-text-white"><?=$price?></text><br>
			<b class="has-text-white">Sales: </b><text class="has-text-white"><?=mysqli_num_rows(getItemSales($item['id']))?></text><br>
		</div>
		<div class="box">
			<h3 class="subtitle has-text-white">Owners</h3>
			<ol>
				<?php foreach(getItemSales($item['id']) as $owners){ 
					$owner = userInfo($owners['buyer']);
					?>
					<li class="has-text-white"><?=$owner['username']?></li>
					<?php } ?>
				</ol>
			</div>
		</div>
	</div>
	<script>
	$("#reset").click(function(){
		$.post("/admin/posts/moderation", {
			user: $("#uid").val(),
			reset: $("#reset").val()
		}).done(function(resp){
			if(resp != "succ"){
				$("#error").text(resp)
			}else{
				$("#succ").text("Successfully resetted username");
			}
		}).fail(function(){
			alert("There was an error with this request");
		})
	})
	</script>