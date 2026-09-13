<?php
$pageName = "Forums";
include('../config/param.php');
include('../config/header.php');
$one = 1;
$getSubForums = $conn->prepare("SELECT * FROM `forum_tables`");
$getSubForums->execute();
$sub = $getSubForums->get_result();
$s = $sub->fetch_assoc();
if(isset($_GET['id'])){
$getSubForums = $conn->prepare("SELECT * FROM `forum_tables` WHERE `id`=?");
$getSubForums->bind_param("i", $_GET['id']);
$getSubForums->execute();
$sub2 = $getSubForums->get_result();
$s = $sub2->fetch_assoc();
	if(mysqli_num_rows($sub2) > 0){
}else{
	header("Location: /error/code/404");
}
}
if(!isset($_GET['id'])){
$getThreads = $conn->prepare("SELECT * FROM `forum_threads` WHERE `board_id`=? AND `hidden`=? ORDER BY `updated` DESC");
$getThreads->bind_param("ii", $one, $o);
$getThreads->execute();
}else{
$getThreads = $conn->prepare("SELECT * FROM `forum_threads` WHERE `board_id`=? AND `hidden`=? ORDER BY `updated` DESC");
$getThreads->bind_param("ii", $_GET['id'], $o);
$getThreads->execute();
}
$forumThreads = $getThreads->get_result();
?>
<section class="section">
<center>
<h1 class='title has-text-white'><?=$s['sub_name']?></h1>
<h1 class='subtitle has-text-white'><?=$s['sub_desc']?></h1>
</center>
<br>
		<section class="container">
			<div class="columns">
				<div class="column is-3">
					<a class="button is-dark is-block is-alt is-large" href="/forums/newpost/<?=$s['id']?>">New Post</a>
					<br>
					<aside class="menu">
						<p class="menu-label">
							Subforums
						</p>
						<ul class="menu-list">
							<?php
							foreach($sub as $subforum){
								?>
								<li><a style='background-color:<?=$subforum['color']?>;color:white;border-radius:0px;' href="/forums/?id=<?=$subforum['id']?>"><?=$subforum['sub_name']?></a></li>
								<?php
							}
							?>
                        </ul>
					</aside>
				</div>
				<div class="column is-9">
					<?php while($thread = $forumThreads->fetch_assoc()){ 
                    $creator = userInfo($thread['creator']);

                    $getReplies = $conn->prepare("SELECT * FROM `forum_replies` WHERE `thread`=?");
                    $getReplies->bind_param("i", $thread['id']);
                    $getReplies->execute();
                    $replynum = $getReplies->get_result();
                    $reply = mysqli_num_rows($replynum);
						?>
<div class="box content">
						<article class="post">
							<div class="media">
								<div class="media-left">
								<img style="width:50px;" src="<?=$creator['avatar_img']?>">
								</div>
								<div class="media-content">
									<div class="content">
										<a href='/forums/thread/<?=$thread['id']?>'>
											<p class='has-text-white'>
												<?=filter(htmlentities($thread['title']))?>
												<?php if($thread['locked'] != 0){ ?>
        											<i class="fa fa-lock"></i>
        										<?php } ?>
											</p>
										</a>
										<p class='has-text-white'>
											By: <a href="/profile/<?=$creator['username']?>"><?=$creator['username']?></a>. Created <?=time_elapsed2($thread['time'])?> &nbsp;
										</p>
									</div>
								</div>
								<div class="media-right">
									<span class="has-text-grey-light"><i class="fa fa-comments"></i> <?=$reply?></span>
								</div>
							</div>
						</article>
					</div>
					<?php
				}
				?>
				</div>
			</div>
		</section>
  </section>
<?php
footer();
?>