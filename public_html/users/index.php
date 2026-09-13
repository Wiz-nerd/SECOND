<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/param.php');
$pageName = "Users";
include($_SERVER['DOCUMENT_ROOT'] . '/config/header.php');
?>
<section class="section">
  <div class="container">
    <div class="columns">
      <div class="column is-3">
        <div class="box">
          <p class="has-text-white is-centered subtitle">Recently Registered Users</p>
          <hr>
          <div id="new-users"></div>
        </div>
      </div>
      <div class="column is-8">
        <div class="box">
          <p class="has-text-white subtitle">Search Users</p>
          <input onkeydown="search()" id="search" placeholder="Enter username here" class="input is-dark">
          <p class="help is-danger"></p>
          <div id="users"></div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
footer();
?>
<script>
  $(document).ready(function(){
    $("#new-users").load("/users/get-r-users.php");
    $("#users").load("/users/get-users.php");
  });

  function search(){
    var search = $("#search").val()
    $("#users").load("/users/get-users.php?search="+search+"");
  }

  function getPage(page, cat){
    $("#users").load("/users/get-users.php?search="+$("#search").val()+"&page="+page+"");
  }
</script>