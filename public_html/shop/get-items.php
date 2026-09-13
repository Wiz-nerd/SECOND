<?php
include("../config/param.php");
include_once($_SERVER['DOCUMENT_ROOT'] . '/shop/itemBackend.php');

if(!isset($_GET['sort'])){
	die();
}

if(!isset($_GET['page'])) {
    $page = 1;
} else {
    $page = $_GET['page'];
}
if(!is_numeric($page)) {
    die();
}

$offset = ($page - 1) * 12;
if($offset < 0) { $offset = 0; }

$getItems = $conn->prepare("SELECT * FROM `items_brz` WHERE `type`=? AND `pending`=? AND `deleted`=? ORDER BY `id` DESC LIMIT 12 OFFSET $offset");
$getItems->bind_param("sii", $_GET['sort'], $o, $o) or die($conn->error);
$getItems->execute() or die($conn->error);
$itemR = $getItems->get_result() or die($conn->error);
$iR = $conn->query("SELECT * FROM `items_brz` WHERE `type`='$_GET[sort]' AND `deleted`=0 AND `pending`=0") or die(mysqli_error($conn));
$pages = ceil($iR->num_rows / 12);
?>
<div class="columns is-multiline">
	<?php
	if(mysqli_num_rows($itemR) > 0){
		foreach($itemR as $item){
			$creator = userInfo($item['creator']);
			switch($item['status']){
				case 'onsale':
				$price = "$item[item_price] ";
				break;
				case 'offsale':
				$price = "Offsale";
				break;
				case 'free':
				$price = "Free";
				break;
				case 'limited':
                $getReselling = $conn->prepare("SELECT * FROM `item_selling_brz` WHERE `item_id`=? ORDER BY `price` ASC");
                $getReselling->bind_param("i", $item['id']);
                $getReselling->execute();
                $resellingRes = $getReselling->get_result();
                if($item['stock_left'] != 0){
					$price = "$item[item_price] ";
                }elseif($resellingRes->num_rows == 0){
                  	$price = "No resellers.";
                }else{
                 	$price = $resellingRes->fetch_assoc()['price'];
                }
				break;
				default:
				$price = "???";
			}
          
            if($item['status'] == "limited"){
              $box = "<div class=\"box\" style=\"background-image:linear-gradient(to right, #947a27 , #e5c063)\">";
            }else if($item['is_crate'] == "yes"){
              $box = "<div class=\"box\" style=\"background-image:linear-gradient(to right, #0096FF , #89CFF0)\">";
            }else{
              $box = "<div class=\"box\">";
            }
			?>
			<div class="column is-2">
				<?=$box?>
					<div class="has-text-centered">
						<a href="/shop/item/<?=$item['id']?>">
							<img class="image is-centered" width="122" height="122" src="<?=$item['headshot']?>">
						</a>
						<div>
							<text class="truncate" title="<?=$item['item_name']?>">
								<a class="has-text-white title is-6 truncate" href="/shop/item/<?=$item['id']?>"><?=$item['item_name']?></a></text>
								<text class="has-text-white truncate">
								By: <a class='has-text-white' href="/profile/<?=$creator['username']?>"><?=$creator['username']?></a>
							</text>
							<text class="has-text-white"><?=$price?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 20010904//EN"
 "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">
<svg style="margin-left: 2px;margin-bottom:-2.5px" version="1.0" xmlns="http://www.w3.org/2000/svg"
 width="18px" height="18px" viewBox="0 0 512.000000 512.000000"
 preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
fill="white" stroke="none">
<path d="M2200 5111 c-72 -22 -151 -82 -215 -165 -58 -75 -137 -232 -172 -344
-25 -77 -28 -101 -27 -207 1 -86 8 -142 23 -198 28 -106 102 -278 176 -406 58
-103 62 -107 110 -123 85 -29 182 -23 331 19 112 33 142 38 229 37 90 -1 115
-5 249 -47 82 -26 156 -47 163 -47 21 0 144 73 243 144 95 67 269 236 318 307
48 71 98 176 132 277 71 211 17 359 -143 392 -29 5 -173 12 -322 13 -368 5
-400 14 -645 179 -161 108 -202 132 -278 157 -62 21 -126 26 -172 12z"/>
<path d="M1238 3904 c-33 -17 -58 -62 -58 -103 0 -50 39 -85 122 -109 69 -20
209 -95 272 -146 l28 -24 -63 -11 c-103 -18 -392 -110 -416 -133 -30 -28 -38
-76 -20 -121 15 -34 59 -67 91 -67 8 0 85 22 170 49 184 59 252 74 324 69 l54
-3 -129 -110 c-437 -375 -766 -805 -891 -1165 -56 -161 -75 -278 -76 -455 0
-141 3 -173 27 -268 46 -186 106 -315 220 -477 251 -355 536 -574 906 -696
451 -149 1051 -174 1518 -64 247 58 516 174 675 293 498 369 621 977 336 1667
-188 457 -500 842 -1134 1402 -14 13 -62 24 -160 39 -76 12 -173 32 -214 46
-43 14 -111 27 -160 30 -75 5 -101 1 -215 -28 -159 -41 -298 -46 -378 -15 -82
31 -205 104 -355 208 -152 107 -250 160 -347 190 -72 21 -91 22 -127 2z m1477
-877 c64 -35 85 -89 85 -212 l0 -101 73 -23 c243 -78 408 -238 395 -383 -4
-41 -11 -57 -35 -79 -68 -60 -144 -44 -214 47 -51 67 -122 131 -177 162 l-42
23 0 -303 0 -304 97 -27 c237 -67 372 -187 428 -382 21 -72 24 -217 6 -295
-49 -209 -208 -357 -442 -412 l-89 -21 0 -94 c0 -110 -13 -147 -65 -190 -63
-52 -150 -50 -207 4 -46 43 -58 81 -58 187 0 104 4 97 -70 111 -66 12 -161 47
-228 83 -191 105 -302 326 -219 436 45 59 120 60 174 3 17 -18 50 -59 74 -92
66 -92 129 -149 199 -183 l65 -31 3 336 c1 185 0 338 -2 340 -2 3 -46 17 -98
32 -118 34 -215 88 -287 160 -241 241 -185 650 111 814 70 39 163 72 233 82
l45 7 0 97 c0 80 4 104 23 141 27 54 84 90 142 90 23 0 57 -10 80 -23z"/>
<path d="M2403 2464 c-23 -9 -63 -34 -89 -57 -121 -107 -128 -297 -14 -387 32
-25 145 -80 165 -80 3 0 5 122 5 270 0 210 -3 270 -12 269 -7 0 -32 -7 -55
-15z"/>
<path d="M2800 1250 c0 -194 3 -290 10 -290 6 0 27 9 48 19 109 56 182 167
182 276 0 34 -5 77 -11 96 -25 73 -91 137 -178 170 l-51 19 0 -290z"/>
</g>
</svg>
</text>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}else{
		print("<p class='has-text-white'>There are no items in this category</p>");
	}
	echo'</div><div class="pagination is-centered is-mobile">';
    if(intval($page)!=1){ ?>
    <a class='button is-success' onclick='getPage(<?=strval(intval($page) - 1)?>, "<?=$_GET['sort']?>")'>Previous Page</a>
    <?php
    }else{ ?>
    <a class='button is-success' disabled="true">Previous Page</a>
    <?php
    }
    ?>
    <p class='has-text-white'>&nbsp; Page <?=$page?> of <?=$pages?>&nbsp;</p>
    <a class='button is-success' <?=($page + 1 > $pages) ? "disabled='disabled'" : "onclick='getPage(".strval(intval($page) + 1).", &quot;$_GET[sort]&quot;)'"?>>Next Page</a>
    <br><br></div>