<?php
include("../../config/param.php");
if(isset($_GET['sort']) && isset($_GET['page'])) {
	$page = mysqli_real_escape_string($conn,$_GET['page']);
	$type = mysqli_real_escape_string($conn,$_GET['sort']);
	$itemsSQL = "SELECT `item` FROM `brz_inv` WHERE `buyer`='$user[id]' ORDER BY `id` DESC";
	$itemsResult = $conn->query($itemsSQL);
	if(mysqli_num_rows($itemsResult) != 0){
		$findav = mysqli_query($conn,"SELECT * FROM `brz_avatar` WHERE `user_id` = '$user[id]'");
		$av = mysqli_fetch_array($findav);
		$invItems = array();
		echo '<div class="columns is-multiline">';
		while($row=$itemsResult->fetch_assoc()){
			$invItems[] = $row['item'];
		}
		$shopItemsSQL = "SELECT * FROM `items_brz` WHERE `id` IN (".implode(',',array_map('intval',$invItems)).") AND `type`='$type'";
		$shopItems = $conn->query($shopItemsSQL);
		$items = isset($shopItems->num_rows);
		$r = 0;
		$count = 1;
		if(mysqli_num_rows($shopItems) != 0){
			while($itemRow=$shopItems->fetch_assoc()){
				if ($count%4 == 1) {

				}
				$r++;
				?>
				<div class="column is-3">
					<div class="box is-centered">
						<a href="/shop/item/<?=$itemRow["id"]?>">
							<img src="<?=$itemRow['headshot']?>"></img>
						</a><br>
						<a class="truncate" href="/shop/item/<?=$itemRow["id"]?>"><?=htmlentities($itemRow['item_name'])?></a>
						<button name="<?=$itemRow["id"]?>" onclick="wear(<?=$itemRow["id"]?>)" class="button is-success is-fullwidth">Equip</button>
					</div></div>
					<?php
					/*if ($count%4 == 0) {
						echo "</div>";
					}*/
					$count++;
				}
				if ($count%4 != 1) { echo "</div>"; }
			}else{ ?>
			<text class="has-text-white is-centered">There are no items in this category</text>
			<?php }
		}else{ ?>
		<text class="has-text-white is-centered">There are no items in this category</text>
		<?php }
		echo '</div></div>';
	} else {
		die();
	}
	?>