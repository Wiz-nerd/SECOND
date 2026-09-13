<?php
$pageName = "Welcome";
include_once($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
include_once($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
$getUsers = $conn->query("SELECT * FROM `brz_users`");
$users = $getUsers->num_rows;
?>
<section class="section">
  <div class="container">
  <center>
    <h1 class='title has-text-white'><?=$site[0]?>. Breaking the limits of creativity.</h1><br>
    <h3 class='subtitle has-text-white'><?=$site[0]?> is an online social hangout where over <?=$users?> users get to hang out together, buy, sell and trade items, make new friends and so much more.</h3>
    <br>
    <div class="columns">
      <div class="column is-4">
        <div class='box'>
          <h2 class='title has-text-white'>Tons of users!</h2>
          <h3 class='subtitle has-text-white' style='width:300px;height:200px;overflow:hidden;'>
            There is alot you can do on <?=$site[0]?>, such as buying items, playing user made games, chatting on our forum,
            making new friends and so much more!</h3>
        </div>
      </div>
      <div class="column is-4">
        <div class='box'>
          <h2 class='title has-text-white'>Non-stop growth!</h2>
          <h3 class='subtitle has-text-white' style='width:300px;height:200px;overflow:hidden;'>That's right, ever since January of 2021, <?=$site[0]?> has
            never stopped growing! New users coming in everyday doing all of the activities mentioned!</h3>
        </div>
      </div>
      <div class="column is-4">
        <div class='box'>
          <h2 class='title has-text-white'>No limits to creativity!</h2>
          <h3 class='subtitle has-text-white' style='width:300px;height:200px;overflow:hidden;'>
            And we mean it! On <?=$site[0]?>, there are so many things to think about and to create that an uninspired person
            can become an inspired, full of ideas and creative person!</h3>
        </div>
      </div>
    </div>
    <br><h2 class='is-size-4 has-text-white'>What are you waiting for? Join <?=$site[0]?> today!</h2>
    <br>
    <button onclick="window.location='/register/'" class='button is-large is-dark'>Register</button>
  </center>
    </div>
</section>
<?php
  footer();
?>