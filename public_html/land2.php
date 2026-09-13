<?php
include_once($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$getUserCount = $conn->prepare("SELECT * FROM `brz_users`");
$getUserCount->execute();
$userResult = $getUserCount->get_result();
$getItemCount = $conn->prepare("SELECT * FROM `items_brz`");
$getItemCount->execute();
$ItemResult = $getItemCount->get_result();
$getGameCount = $conn->prepare("SELECT * FROM `brz_games` ORDER BY RAND() DESC LIMIT 5");
$getGameCount->execute();
$GameResult = $getGameCount->get_result();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Landing | <?=$site[0]?></title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="../images/fav_icon.png" type="image/x-icon">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link rel="stylesheet" href="https://unpkg.com/bulma@0.9.0/css/bulma.min.css" />
	<link rel="stylesheet" type="text/css" href="https://bulmatemplates.github.io/bulma-templates/css/hero.css">
	<link rel="stylesheet" href="https://unpkg.com/bulma-modal-fx/dist/css/modal-fx.min.css" />
	<link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
	<link rel="shortcut icon" href="<?=$site[1]?>">
</head>
<style>
.swiper {
	width: 100%;
	height: 100%;
}

.swiper-slide {
	text-align: center;
	font-size: 18px;

	/* Center slide text vertically */
	display: -webkit-box;
	display: -ms-flexbox;
	display: -webkit-flex;
	display: flex;
	-webkit-box-pack: center;
	-ms-flex-pack: center;
	-webkit-justify-content: center;
	justify-content: center;
	-webkit-box-align: center;
	-ms-flex-align: center;
	-webkit-align-items: center;
	align-items: center;
}

.swiper-slide img {
	display: block;
	width: 80%;
	height: 80%;
}
</style>
<body>
	<section style="background:#fe8447;" class="hero is-info is-medium is-bold">
		<div class="hero-head">
			<nav class="navbar">
				<div class="container">
					<div class="navbar-brand">
						<a class="navbar-item" href="../">
							<p class="is-size-4 has-text-white"><?=$site[0]?></p>
						</a>
						<span class="navbar-burger burger" data-target="navbarMenu">
							<span></span>
							<span></span>
							<span></span>
						</span>
					</div>
					<div id="navbarMenu" class="navbar-menu">
						<div class="navbar-end">
							<div class="tabs is-right">
								<ul>
									<li><a href="/login/">Login</a></li>
									<li><a href="/register/">Register</a></li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</nav>
		</div>
		<div class="hero-body">
			<div class="container has-text-centered">
				<h1 class="title">
					<?=$site[0]?>. An experience you will never forget.
				</h1>
				<h2 class="subtitle">
					<?=$site[0]?> is a 2D platform where you can play games, customize your avatar with hundreds of user-generated items, make new friendships, and so much more.
				</h2>
			</div>
		</div>
	</section>
	<section class="container">
		<div class="columns features">
			<div class="column is-4">
				<div class="card">
					<div class="card-image has-text-centered">
						<i class="fa fa-users"></i>
					</div>
					<div class="card-content">
						<div class="content">
							<h4>Over <?=$userResult->num_rows?> users worldwide</h4>
							<p>Brickorzo has been experiencing non-stop growth ever since August of 2021. These users are all having fun playing games, making awesome avatars and much more.</p>
							<p><a href="/users/">View Users</a></p>
						</div>
					</div>
				</div>
			</div>
			<div class="column is-4">
				<div class="card">
					<div class="card-image has-text-centered">
						<i class="fa fa-shopping-basket"></i>
					</div>
					<div class="card-content">
						<div class="content">
							<h4>A fun and intuitive economy</h4>
							<p>Not only you get to play games, but you can also participate in its tireless economy! With over <?=$ItemResult->num_rows?> items to collect and equip to make breath-taking avatars.</p>
							<p><a href="/shop/">View Shop</a></p>
						</div>
					</div>
				</div>
			</div>
			<div class="column is-4">
				<div class="card">
					<div class="card-image has-text-centered">
						<i class="fa fa-comments"></i>
					</div>
					<div class="card-content">
						<div class="content">
							<h4>A place to express yourself</h4>
							<p>Not only you can do all of the above, you can even start discussions and express your opinions in our forum! Ranging from your favorite pet to what is your goal in life, you can socialize like never before!</p>
							<p><a href="/forums/">View Forums</a></p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<h1 class="title has-text-centered">Games made by the community</h1>
		<div style="height:10px;"></div>
		<div class="swiper mySwiper">
			<div class="swiper-wrapper">
				<?php foreach($GameResult as $game){ 
					$user = userInfo($game['creator']);
					?>
					<div class="swiper-slide">
						<div class="box">
							<center><a href="/games/game/<?=$game['id']?>">
								<img width="200" height="50" src="<?=$game['thumbnail']?>">
							</a></center>
							<div style="height:10px;"></div>
							<a href="/games/game/<?=$game['id']?>"><h2 class="title"><?=htmlentities($game['title'])?></h2></a>
							<h3 class="subtitle">By <?=$user['username']?></h3><br>
						</div>
					</div>
					<?php
				}
				?>
			</div>
			<div class="swiper-button-next"></div>
			<div class="swiper-button-prev"></div>
			<div class="swiper-pagination"></div>
		</div>
		<br>
	</body>
	</html>
	<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

	<!-- Initialize Swiper -->
	<script>
	var swiper = new Swiper(".mySwiper", {
		spaceBetween: 30,
		centeredSlides: true,
		autoplay: {
			delay: 2000,
			disableOnInteraction: false,
		},
		pagination: {
			el: ".swiper-pagination",
			clickable: true,
		},
		navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev",
		},
	});
	</script>