<?php
$pageName = "Upgrades";
require("../config/param.php");
require("../config/header.php");
if(!isset($_SESSION['id'])){
  header("Location: /login/");
}

//if($user['id'] != 3){ die("<div class='title is-centered has-text-white'>This page is currently undergoing maintenance</div>"); }
?>
<div class="section">
  <div class="container">
    <div class="content">
      <!--<div class="notification is-danger">Memberships are currently disabled for maintenance</div>-->
      <div class="box">
        <h1 class="title has-text-white"><?=$site[0]?> Plus</h1>
        <p class="has-text-white">You can support <?=$site[0]?> & gain special perks by purchasing a membership!</p><br>
        <div class="columns">
          <div class="column is-6">
            <img class="image is-centered" width="115" title="Plus Character" src="/assets/images/plus.png">
          </div>
          <div class="column is-4">
            <ul class="has-text-white">
              <li>15 Daily <?=$site[7]?></li>
              <li>Clan creation cost reduced to 25</li>
              <li>Special Badge & Tag on the forums</li>
              <li>Outfit limit increased to 30 instead of 5</li>
              <li>2 exclusive items</li>
              <li class="has-text-weight-bold">Early access to games</li>
            </ul>
            <?php if($user['membership'] == 0): ?>
            <form class="paypal" action="/upgrade/IPN.php" method="post" id="paypal_form">
                <input type="hidden" name="cmd" value="_xclick" />
                <input type="hidden" name="no_note" value="1" />
                <input type="hidden" name="lc" value="CA" />
                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                <input type="hidden" name="first_name" value="Firstname" />
                <input type="hidden" name="last_name" value="Lastname" />
                <input type="hidden" name="custom" value="<?php echo $user['username'] . ":" . $user['id'] ?>" />
                <input type="hidden" name="payer_email" value="customer@example.com" />
                <input type="hidden" name="item_number" value="<?php echo 1 ?>" / >
                <br><input class="ui button is-black" name='submit' type="submit" value="$2.99 per month">
              </form>
            <?php endif; ?>
          </div>
        </div>
      </div>
      <div class="box">
        <h1 class="title has-text-white"><?=$site[7]?></h1>
        <p class="has-text-white">Instead of buying a membership, you can also buy <?=$site[7]?>.</p><br>
        <div class="columns">
          <div class="column">
            <div class="box">
              <center style="font-size: 18px;">
                <b class="has-text-white">50 <?=$site[7]?></b>
                <text class="help has-text-white">An exclusive item is being offered for a limited time!</text>
              </center>
              <center><form class="paypal" action="/upgrade/IPN.php" method="post" id="paypal_form">
                <input type="hidden" name="cmd" value="_xclick" />
                <input type="hidden" name="no_note" value="1" />
                <input type="hidden" name="lc" value="CA" />
                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                <input type="hidden" name="first_name" value="Firstname" />
                <input type="hidden" name="last_name" value="Lastname" />
                <input type="hidden" name="custom" value="<?php echo $user['username'] . ":" . $user['id'] ?>" />
                <input type="hidden" name="payer_email" value="customer@example.com" />
                <input type="hidden" name="item_number" value="<?php echo 2 ?>" / >
                <br><input class="ui button is-black" name='submit' type="submit" value="Buy for $0.50">
                </form>
              </center>
            </div>
          </div>
          <div class="column">
            <div class="box">
              <center style="font-size: 18px;">
                <b class="has-text-white">250 <?=$site[7]?></b>
                <text class="help has-text-white">An exclusive item is being offered for a limited time!</text>
              </center>
              <center><form class="paypal" action="/upgrade/IPN.php" method="post" id="paypal_form">
                <input type="hidden" name="cmd" value="_xclick" />
                <input type="hidden" name="no_note" value="1" />
                <input type="hidden" name="lc" value="CA" />
                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                <input type="hidden" name="first_name" value="Firstname" />
                <input type="hidden" name="last_name" value="Lastname" />
                <input type="hidden" name="custom" value="<?php echo $user['username'] . ":" . $user['id'] ?>" />
                <input type="hidden" name="payer_email" value="customer@example.com" />
                <input type="hidden" name="item_number" value="<?php echo 3 ?>" / >
                <br><input class="ui button is-black" name='submit' type="submit" value="Buy for $2.50">
                </form>
              </center>
            </div>
          </div>
          <div class="column">
            <div class="box">
              <center style="font-size: 18px;">
                <b class="has-text-white">450 <?=$site[7]?></b>
                <text class="help has-text-white">An exclusive item is being offered for a limited time!</text>
				</center>
              <center><form class="paypal" action="/upgrade/IPN.php" method="post" id="paypal_form">
                <input type="hidden" name="cmd" value="_xclick" />
                <input type="hidden" name="no_note" value="1" />
                <input type="hidden" name="lc" value="CA" />
                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                <input type="hidden" name="first_name" value="Firstname" />
                <input type="hidden" name="last_name" value="Lastname" />
                <input type="hidden" name="custom" value="<?php echo $user['username'] . ":" . $user['id'] ?>" />
                <input type="hidden" name="payer_email" value="customer@example.com" />
                <input type="hidden" name="item_number" value="<?php echo 4 ?>" / >
                <br><input class="ui button is-black" name='submit' type="submit" value="Buy for $4.50">
                </form></center>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?=footer()?>