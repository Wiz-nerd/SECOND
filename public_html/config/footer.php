<?php
$footer = [
  'Terms Of Service |' => '/legal/terms',
  'Privacy Policy |' => '/legal/privacy',
  'Staff |' => '/legal/staff',
  'Credits' => '/legal/credits',
];
?>

<center>
  <br><br><br>
  <footer class='footer is-dark'>
    <img src="<?=$site[1]?>" width="200" height="200">
    <p class='has-text-white is-size-5'><?=date('Y');?> &copy; <?=$site[0]?></p>
    <br>
    <div class='columns is-centered'>
      <?php
  foreach( $footer as $foot => $link){ ?>
      <a href='<?=$link?>'><p class='has-text-white is-size-5'>&nbsp;<?=$foot?></p></a>
      <?php } ?>
    </div>
  </footer>

  <script>
    function toggleBurger() {
      var burger = $('.navbar-burger');
      var menu = $('.navbar-menu');
      burger.toggleClass('is-active');
      menu.toggleClass('is-active');
    }
  </script>